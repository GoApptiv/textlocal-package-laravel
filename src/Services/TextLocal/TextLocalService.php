<?php

namespace GoApptiv\TextLocal\Services\TextLocal;

use Exception;
use GoApptiv\TextLocal\Bo\Sms\TextLocalSms;
use GoApptiv\TextLocal\Exception\InvalidAccountDetailsException;
use GoApptiv\TextLocal\Exception\InvalidMobileNumberException;
use GoApptiv\TextLocal\Models\Sms\BulkSmsLog;
use GoApptiv\TextLocal\Repositories\Account\AccountRepositoryInterface;
use GoApptiv\TextLocal\Repositories\Sms\BulkSmsLogRepositoryInterface;
use GoApptiv\TextLocal\Repositories\Sms\SmsLogRepositoryInterface;
use GoApptiv\TextLocal\Resources\TextLocalErrorSmsResource;
use GoApptiv\TextLocal\Resources\TextLocalSuccessSmsResource;
use GoApptiv\TextLocal\Services\Constants;
use GoApptiv\TextLocal\Services\Crypto;
use GoApptiv\TextLocal\Services\Endpoints;
use GuzzleHttp\Client;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class TextLocalService
{
    /** @var AccountRepositoryInterface */
    private $accountRepository;

    /** @var SmsLogRepositoryInterface */
    private $smsLogRepository;

    /** @var BulkSmsLogRepositoryInterface */
    private $bulkSmsLogRepository;


    /**
     * Constructor
     *
     * @param AccountRepositoryInterface $accountRepository
     * @param SmsLogRepositoryInterface $smsLogRepository
     * @param BulkSmsLogRepositoryInterface $bulkSmsLogRepository
     *
     */
    public function __construct(
        AccountRepositoryInterface $accountRepository,
        SmsLogRepositoryInterface $smsLogRepository,
        BulkSmsLogRepositoryInterface $bulkSmsLogRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->smsLogRepository = $smsLogRepository;
        $this->bulkSmsLogRepository = $bulkSmsLogRepository;
    }

    /**
     * Add TextLocal Account
     *
     * @param string $emailId
     * @param string $apiKey
     * @param string $name
     *
     * @return int
     */
    public function addAccount(string $emailId, string $apiKey, string $name = null): int
    {
        if ($emailId !== null && $apiKey !== null) {
            $data = collect([
                "name" => $name,
                "email" => $emailId,
                "api_key" => $apiKey
            ]);

            $account = $this->accountRepository->store($data->toArray());

            return $account->id;
        } else {
            throw new InvalidAccountDetailsException();
        }
    }

    /**
     * Replace Account API Key
     *
     * @param int $accountId
     * @param string $apiKey
     *
     * @return bool
     */
    public function replaceApiKey(int $accountId, string $apiKey): bool
    {
        return $this->accountRepository->update($accountId, ['api_key' => $apiKey]) > 0 ? true : false;
    }

    /**
     * Send SMS
     *
     * @param Collection $mobileNumbers
     * @param int $accountId
     * @param string $message
     * @param string $sender
     *
     * @return
     */
    public function sendSMS(TextLocalSms $data, int $accountId)
    {
        Log::info("SENDING SMS USING SENDER " . $data->getSender() . " AND ACCOUNT ID " . $accountId);

        $account = $this->accountRepository->find($accountId);

        if ($account == null) {
            throw new Exception("Invalid Account");
        }

        // Register Batch
        $batch = $this->registerBulkSmsBatch($accountId, $data->getMobileNumbers()->count());
        $smsStoreData = collect([]);

        // Generate SMS Log Data
        foreach ($data->getMobileNumbers() as $mobile) {
            $smsStoreData->push($this->generateSmsRegistrationData($accountId, $mobile, $data->getMessage(), $data->getSender(), $batch->id));
        }

        // Register SMS Log
        $this->smsLogRepository->bulkStore($smsStoreData->toArray());

        // Encode Data
        $message = urlencode($data->getMessage());
        $numbers = rawurlencode($data->getMobileNumbers()->unique()->join(","));
        $apiKey = urlencode($account->api_key);
        $sender = urlencode($data->getSender());

        $requestData = 'apikey=' . $apiKey . '&numbers=' . $numbers . "&sender=" . $sender . "&message=" . $message;
        $client = $this->getClient();

        $response = $client->request(
            'GET',
            env('TEXTLOCAL_API') . Endpoints::$SMS . '?' . $requestData
        );

        // Mark as Dispatched
        $this->markSmsAsDispatchedByBulkId($batch->id);

        $responseJson = json_decode($response->getBody()->getContents(), true);

        if ($responseJson['status'] == Constants::$SUCCESS) {
            $this->onSmsSuccessResponse($responseJson, $batch->id, $data->getMobileNumbers());
            return TextLocalSuccessSmsResource::make($responseJson)->resolve();
        } else {
            $this->onSmsFailureResponse($responseJson, $batch->id);
            return TextLocalErrorSmsResource::make($responseJson)->resolve();
        }
    }

    /**
     * Register Bulk Sms Batch
     *
     * @param int $accountId
     * @param int $total
     *
     * @return BulkSmsLog
     */
    private function registerBulkSmsBatch(int $accountId, int $total): BulkSmsLog
    {
        return $this->bulkSmsLogRepository->store(collect([
            "account_id" => $accountId,
            "total" => $total,
            "status" => Constants::$PENDING
        ])->toArray());
    }

    /**
     * Generate SMS Registration Data
     *
     * @param int $accountId
     * @param string $mobile
     * @param string $message
     * @param string $sender
     * @param int $bulkId
     *
     * @return array
     */
    private function generateSmsRegistrationData(int $accountId, string $mobile, string $message, string $sender, int $bulkId): array
    {
        $now = now();
        return [
            "account_id" => $accountId,
            "mobile" => $mobile,
            "message" => Crypto::encrypt($message),
            "sender" => $sender,
            "bulk_id" => $bulkId,
            "created_at" => $now,
            "updated_at" => $now
        ];
    }

    /**
     * Mark SMS as Dispatched by Bulk ID
     *
     * @param int $bulkId
     * @return bool
     */
    private function markSmsAsDispatchedByBulkId(int $bulkId): bool
    {
        $bulkLog = $this->bulkSmsLogRepository->update($bulkId, ['status' => Constants::$DISPATCHED]);
        $smsLog = $this->smsLogRepository->updateStatusByBulkId($bulkId, Constants::$DISPATCHED);
        return ($bulkLog & $smsLog);
    }

    /**
     * On SMS Success Response
     *
     * @param array $response
     * @param int $bulkId
     * @param Collection $mobileNumbers
     * @return void
     */
    private function onSmsSuccessResponse(array $response, int $bulkId, Collection $mobileNumbers): void
    {
        if (array_key_exists('messages', $response)) {
            // Update Bulk Log
            $bulkUpdateData = [
                "textlocal_batch_id" => $response['batch_id'],
                "delivered" => count($response['messages']),
                "status" => Constants::$SUCCESS,
            ];
            $this->bulkSmsLogRepository->update($bulkId, $bulkUpdateData);

            // Update Log
            foreach ($response['messages'] as $message) {
                $mobileNumber = $message['recipient'];
                $id = $message['id'];

                foreach ($mobileNumbers as $requestMobileNumber) {
                    if (str_contains($mobileNumber, $requestMobileNumber)) {
                        $this->smsLogRepository->updateByBulkIdAndMobile($bulkId, $requestMobileNumber, ["status" => Constants::$SUCCESS, "textlocal_id" => $id]);
                    }
                }

                // Mark Rest as failed
                $this->smsLogRepository->updateByBulkIdWhereTextLocalIdIsNull($bulkId, ["status" => Constants::$FAILED, "comment" => "No Status received from TextLocal"]);
            }
        }
    }

    /**
     * On Sms Failure Response
     *
     * @param array $response
     * @param int $bulkId
     * @return void
     */
    private function onSmsFailureResponse(array $response, int $bulkId): void
    {
        $comment = "";

        if (array_key_exists('errors', $response)) {
            foreach ($response['errors'] as $error) {
                $comment = $comment . $error['message'] . " | ";
            }
        }

        if (array_key_exists('warnings', $response)) {
            foreach ($response['warnings'] as $error) {
                $comment = $comment . $error['message'] . " | ";
            }
        }

        // Update Bulk Log
        $bulkUpdateData = [
            "delivered" => 0,
            "status" => Constants::$FAILED,
            "comment" => $comment
            ];
        $this->bulkSmsLogRepository->update($bulkId, $bulkUpdateData);

        // Update Sms Log
        $this->smsLogRepository->updateByBulkIdWhereTextLocalIdIsNull($bulkId, ["status" => Constants::$FAILED, "comment" => $comment]);
    }


    /**
     * Get server client
     *
     * @return Client
     */
    private function getClient(): Client
    {
        return new Client([
            'base_uri' => env("TEXTLOCAL_API"),
            'timeout' => 20.0,
            'headers' => [
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ],
        ]);
    }
}

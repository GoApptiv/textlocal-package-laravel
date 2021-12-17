<?php

namespace GoApptiv\TextLocal\Services\TextLocal;

use Exception;
use GoApptiv\TextLocal\Bo\Sms\TextLocalBulkSms;
use GoApptiv\TextLocal\Bo\Sms\TextLocalSms;
use GoApptiv\TextLocal\Exception\InvalidAccountDetailsException;
use GoApptiv\TextLocal\Models\Sms\SmsBatchLog;
use GoApptiv\TextLocal\Repositories\Account\AccountRepositoryInterface;
use GoApptiv\TextLocal\Repositories\Sms\SmsBatchLogRepositoryInterface;
use GoApptiv\TextLocal\Repositories\Sms\SmsLogRepositoryInterface;
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

    /** @var SmsBatchLogRepositoryInterface */
    private $smsBatchLogRepository;


    /**
     * Constructor
     *
     * @param AccountRepositoryInterface $accountRepository
     * @param SmsLogRepositoryInterface $smsLogRepository
     * @param SmsBatchLogRepositoryInterface $smsBatchLogRepository
     *
     */
    public function __construct(
        AccountRepositoryInterface $accountRepository,
        SmsLogRepositoryInterface $smsLogRepository,
        SmsBatchLogRepositoryInterface $smsBatchLogRepository
    ) {
        $this->accountRepository = $accountRepository;
        $this->smsLogRepository = $smsLogRepository;
        $this->smsBatchLogRepository = $smsBatchLogRepository;
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
     *
     * @return array
     */
    public function sendSMS(TextLocalSms $data, int $accountId): array
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

        $requestData = 'apikey=' . $apiKey . '&numbers=' . $numbers . "&sender=" . $sender . "&message=" . $message . "&test=true";
        $responseJson = $this->makeGetRequest(env('TEXTLOCAL_API') . Endpoints::$SMS . '?' . $requestData);

        // Mark as Dispatched
        $this->markSmsAsDispatchedByBatchId($batch->id);

        if ($responseJson['status'] == Constants::$SUCCESS) {
            $this->onSmsSuccessResponse($responseJson, $batch->id, $data->getMobileNumbers());
        } else {
            $this->onSmsFailureResponse($responseJson, $batch->id);
        }

        return $responseJson;
    }

    /**
     * Send Bulk Sms
     *
     * @param TextLocalBulkSms $data
     * @param int $accountId
     *
     * @return array
     */
    public function sendBulkSms(TextLocalBulkSms $data, int $accountId): array
    {
        Log::info("SENDING BULK SMS USING ACCOUNT ID " . $accountId);

        $account = $this->accountRepository->find($accountId);
        $sender = $data->getSender();

        if ($account == null) {
            throw new Exception("Invalid Account");
        }

        // Register Batch
        $batch = $this->registerBulkSmsBatch($accountId, $data->getTextLocalMessages()->count());

        $messages = collect([]);
        $smsStoreData = collect([]);
        foreach ($data->getTextLocalMessages() as $requestMessage) {
            $messages->push(
                [
                    "number" => $requestMessage->getMobileNumber(),
                    "text" => rawurlencode($requestMessage->getMessage()),
                ]
            );
            $smsStoreData->push($this->generateSmsRegistrationData($accountId, $requestMessage->getMobileNumber(), $requestMessage->getMessage(), $data->getSender(), $batch->id));
        }

        // Register SMS Log
        $this->smsLogRepository->bulkStore($smsStoreData->toArray());

        $requestData = [
            "messages" => $messages->toArray()
        ];
        $responseJson = $this->makeGetRequest(env("TEXTLOCAL_API") . Endpoints::$BULK_SMS . '?' . 'apikey=' . urlencode($account->api_key) . "&sender=" . $sender . "&data=" . json_encode($requestData));

        // Mark as Dispatched
        $this->markSmsAsDispatchedByBatchId($batch->id);

        if (array_key_exists('messages_not_sent', $responseJson)) {
            $this->onBulkSmsFailureResponse($batch->id, $responseJson['messages_not_sent'], $data->getTextLocalMessages());
        }
        if (array_key_exists('messages', $responseJson)) {
            $this->onBulkSmsSuccessResponse($batch->id, $responseJson['messages']);
        }

        return $responseJson;
    }

    /**
     * Register Bulk Sms Batch
     *
     * @param int $accountId
     * @param int $total
     *
     * @return SmsBatchLog
     */
    private function registerBulkSmsBatch(int $accountId, int $total): SmsBatchLog
    {
        return $this->smsBatchLogRepository->store(collect([
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
     * @param int $batchId
     *
     * @return array
     */
    private function generateSmsRegistrationData(int $accountId, string $mobile, string $message, string $sender, int $batchId): array
    {
        $now = now();
        return [
            "account_id" => $accountId,
            "mobile" => $mobile,
            "message" => Crypto::encrypt($message),
            "sender" => $sender,
            "batch_id" => $batchId,
            "created_at" => $now,
            "updated_at" => $now
        ];
    }

    /**
     * Mark SMS as Dispatched by Batch ID
     *
     * @param int $batchId
     * @return bool
     */
    private function markSmsAsDispatchedByBatchId(int $batchId): bool
    {
        $batchLog = $this->smsBatchLogRepository->update($batchId, ['status' => Constants::$DISPATCHED]);
        $smsLog = $this->smsLogRepository->updateStatusByBatchId($batchId, Constants::$DISPATCHED);
        return ($batchLog & $smsLog);
    }

    /**
     * On SMS Success Response
     *
     * @param array $response
     * @param int $batchId
     * @param Collection $mobileNumbers
     * @return void
     */
    private function onSmsSuccessResponse(array $response, int $batchId, Collection $mobileNumbers): void
    {
        if (array_key_exists('messages', $response)) {
            // Update Bulk Log
            $bulkUpdateData = [
                "delivered" => count($response['messages']),
                "status" => Constants::$SUCCESS,
            ];
            $this->smsBatchLogRepository->update($batchId, $bulkUpdateData);

            // Update Log
            foreach ($response['messages'] as $message) {
                $mobileNumber = $message['recipient'];

                foreach ($mobileNumbers as $requestMobileNumber) {
                    if (str_contains($mobileNumber, $requestMobileNumber)) {
                        $this->smsLogRepository->updateByBatchIdAndMobile($batchId, $requestMobileNumber, ["status" => Constants::$SUCCESS]);
                    }
                }

                // Mark Rest as failed
                $this->smsLogRepository->updateByBatchIdAndStatus($batchId, Constants::$DISPATCHED, ["status" => Constants::$FAILED, "comment" => "No Status received from TextLocal"]);
            }
        }
    }

    /**
     * On Sms Failure Response
     *
     * @param array $response
     * @param int $batchId
     * @return void
     */
    private function onSmsFailureResponse(array $response, int $batchId): void
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
        $this->smsBatchLogRepository->update($batchId, $bulkUpdateData);

        // Update Sms Log
        $this->smsLogRepository->updateByBatchIdAndStatus($batchId, Constants::$DISPATCHED, ["status" => Constants::$FAILED, "comment" => $comment]);
    }

    /**
     * On Bulk Sms Success Response
     *
     * @param int $batchId
     * @param array $successfulMessages
     *
     * @return void
     */
    private function onBulkSmsSuccessResponse(int $batchId, array $successfulMessages): void
    {
        $this->smsBatchLogRepository->update($batchId, ['status' => Constants::$SUCCESS, 'delivered' => count($successfulMessages)]);

        // Mark Every Message as success where status is dispatched
        $this->smsLogRepository->updateByBatchIdAndStatus($batchId, Constants::$DISPATCHED, ['status' => Constants::$SUCCESS]);
    }

    /**
     * On Bulk Sms Failure Response
     *
     * @param int $batchId
     * @param array $messageNotSentResponse
     * @param Collection $messages
     *
     * @return void
     */
    private function onBulkSmsFailureResponse(int $batchId, array $messageNotSentResponse, Collection $messages): void
    {
        if (count($messageNotSentResponse) === $messages->count()) {
            $this->smsBatchLogRepository->update($batchId, ['status' => Constants::$FAILED]);
        }

        foreach ($messageNotSentResponse as $messageNotSent) {
            foreach ($messages as $message) {
                if ($messageNotSent['number'] == $message->getMobileNumber() && $messageNotSent['message'] == $message->getMessage()) {
                    $error = $messageNotSent['error']['message'];
                    $updatePayload = ['status' => Constants::$FAILED, 'comment' => $error];
                    $this->smsLogRepository->updateByBatchIdAndMobileAndMessage($batchId, $message->getMobileNumber(), Crypto::encrypt($message->getMessage()), $updatePayload);
                }
            }
        }
    }

    /**
     * Make Get Request
     *
     * @param string $url
     */
    private function makeGetRequest(string $url): array
    {
        $client = $this->getClient();

        $response = $client->request(
            'GET',
            $url
        );

        return json_decode($response->getBody()->getContents(), true);
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

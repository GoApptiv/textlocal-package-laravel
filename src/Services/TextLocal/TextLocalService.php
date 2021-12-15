<?php

namespace GoApptiv\TextLocal\Services\TextLocal;

use Exception;
use GoApptiv\TextLocal\Exception\InvalidAccountDetailsException;
use GoApptiv\TextLocal\Exception\InvalidMobileNumberException;
use GoApptiv\TextLocal\Repositories\Account\AccountRepositoryInterface;
use GoApptiv\TextLocal\Repositories\Sms\BulkSmsLogRepositoryInterface;
use GoApptiv\TextLocal\Repositories\Sms\SmsLogRepositoryInterface;
use Illuminate\Support\Collection;

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
    public function addAccount(string $emailId, string $apiKey, string $name = null)
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
    public function replaceApiKey(int $accountId, string $apiKey)
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
     * @param bool $ignoreInvalidMobile
     *
     * @throws InvalidMobileNumberException
     * @return
     */
    public function sendSMS(Collection $mobileNumbers, int $accountId, string $message, string $sender, bool $ignoreInvalidMobile = true)
    {
    }
}

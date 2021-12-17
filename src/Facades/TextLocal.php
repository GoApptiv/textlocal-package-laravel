<?php

namespace GoApptiv\TextLocal\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static GoApptiv\TextLocal\Service\TextLocalService sendSMS(TextLocalSms $data, int $accountId)
 * @method static GoApptiv\TextLocal\Service\TextLocalService sendBulkSms(TextLocalBulkSms $data, int $accountId)
 */
class TextLocal extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'goapptiv-textlocal';
    }
}

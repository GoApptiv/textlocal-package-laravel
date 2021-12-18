<?php

namespace GoApptiv\TextLocal\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static GoApptiv\TextLocal\Service\TextLocalService sendSMS(TextLocalSms $data, int $accountId, Carbon $scheduledDateTime = null)
 * @method static GoApptiv\TextLocal\Service\TextLocalService sendBulkSms(TextLocalBulkSms $data, int $accountId, Carbon $scheduledDateTime = null)
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

<?php

namespace GoApptiv\TextLocal\Models\Sms;

use GoApptiv\TextLocal\Models\BaseModel;

/**
 *
 * @property int $id - Id of the Message
 * @property int $account_id - Account id from the textlocal_accounts table
 * @property string $mobile - Mobile number
 * @property string $sender - Sender Id for the SMS
 * @property string $status - Status of the SMS
 * @property int $batch_id - Batch Id if the message is in Batch
 * @property string $comment - Comment or error
 *
 **/
class SmsLog extends BaseModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'textlocal_sms_logs';

    /**
     * The attributes that are not mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at', 'updated_at',
    ];
}

<?php

namespace GoApptiv\TextLocal\Models\Sms;

use GoApptiv\TextLocal\Models\BaseModel;

/**
 *
 * @property int $id - Id of the Bulk Message
 * @property int $account_id - Account id from the textlocal_accounts table
 * @property int $total - Total messages in the bulk
 * @property int $delivered - Total delivered message
 * @property string $status - Status of the Bulk Batch
 * @property DateTime $scheduled_datetime - Message Scheduled Date Time
 * @property string $comment - Comment or error
 *
 **/
class SmsBatchLog extends BaseModel
{
    /**
     * Table name
     *
     * @var string
     */
    protected $table = 'textlocal_sms_batch_logs';

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

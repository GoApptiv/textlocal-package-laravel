<?php

namespace GoApptiv\TextLocal\Repositories\MySql\Sms;

use GoApptiv\TextLocal\Models\Sms\SmsLog;
use GoApptiv\TextLocal\Repositories\Sms\SmsLogRepositoryInterface;
use GoApptiv\TextLocal\Repositories\MySql\MySqlBaseRepository;

class SmsLogRepository extends MySqlBaseRepository implements SmsLogRepositoryInterface
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * BaseRepository constructor.
     *
     * @param Model $model
     */
    public function __construct(SmsLog $model)
    {
        $this->model = $model;
    }
}

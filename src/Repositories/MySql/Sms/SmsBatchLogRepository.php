<?php

namespace GoApptiv\TextLocal\Repositories\MySql\Sms;

use GoApptiv\TextLocal\Models\Sms\SmsBatchLog;
use GoApptiv\TextLocal\Repositories\MySql\MySqlBaseRepository;
use GoApptiv\TextLocal\Repositories\Sms\SmsBatchLogRepositoryInterface;

class SmsBatchLogRepository extends MySqlBaseRepository implements SmsBatchLogRepositoryInterface
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
    public function __construct(SmsBatchLog $model)
    {
        $this->model = $model;
    }
}

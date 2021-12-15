<?php

namespace GoApptiv\TextLocal\Repositories\MySql\Sms;

use GoApptiv\TextLocal\Models\Sms\BulkSmsLog;
use GoApptiv\TextLocal\Repositories\Sms\BulkSmsLogRepositoryInterface;
use GoApptiv\TextLocal\Repositories\MySql\MySqlBaseRepository;

class BulkSmsLogRepository extends MySqlBaseRepository implements BulkSmsLogRepositoryInterface
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
    public function __construct(BulkSmsLog $model)
    {
        $this->model = $model;
    }
}

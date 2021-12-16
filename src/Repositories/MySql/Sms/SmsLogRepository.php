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

    /**
     * Bulk Store
     *
     * @param array $payload
     * @return bool
     */
    public function bulkStore(array $payload): bool
    {
        return SmsLog::insert($payload);
    }

    /**
     * Update Status by Bulk Id
     *
     * @param int $bulkId
     * @param string $status
     */
    public function updateStatusByBulkId(int $bulkId, string $status): int
    {
        return SmsLog::with([])->where(['bulk_id' => $bulkId])->update(['status' => $status]);
    }

    /**
     * Update by Bulk Id and Mobile
     *
     * @param int $bulkId
     * @param string $mobile
     * @param array $payload
     * @return int
     */
    public function updateByBulkIdAndMobile(int $bulkId, string $mobile, array $payload): int
    {
        $model = SmsLog::with([])->where(["bulk_id" => $bulkId, "mobile" => $mobile]);
        return $model->update($payload);
    }

    /**
     * Update by Bulk Id Where textlocal_id is null
     *
     * @param int $bulkId
     * @param array $payload
     * @return int
     */
    public function updateByBulkIdWhereTextLocalIdIsNull(int $bulkId, array $payload): int
    {
        $model = SmsLog::with([])->where(["bulk_id" => $bulkId, "textlocal_id" => null]);
        return $model->update($payload);
    }
}

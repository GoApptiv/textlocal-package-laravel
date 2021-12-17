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
     * Update Status by Batch Id
     *
     * @param int $batchId
     * @param string $status
     */
    public function updateStatusByBatchId(int $batchId, string $status): int
    {
        return SmsLog::with([])->where(['batch_id' => $batchId])->update(['status' => $status]);
    }

    /**
     * Update by Batch Id and Mobile
     *
     * @param int $batchId
     * @param string $mobile
     * @param array $payload
     * @return int
     */
    public function updateByBatchIdAndMobile(int $batchId, string $mobile, array $payload): int
    {
        $model = SmsLog::with([])->where(["batch_id" => $batchId, "mobile" => $mobile]);
        return $model->update($payload);
    }

    /**
     * Update by Batch Id, Mobile And Message
     *
     * @param int $batchId
     * @param string $mobile
     * @param string $message
     * @param array $payload
     * @return int
     */
    public function updateByBatchIdAndMobileAndMessage(int $batchId, string $mobile, string $message, array $payload): int
    {
        $model = SmsLog::with([])->where(["batch_id" => $batchId, "mobile" => $mobile, "message" => $message]);
        return $model->update($payload);
    }

    /**
     * Update by Batch Id Where textlocal_id is null
     *
     * @param int $batchId
     * @param array $payload
     * @return int
     */
    public function updateByBatchIdWhereTextLocalIdIsNull(int $batchId, array $payload): int
    {
        $model = SmsLog::with([])->where(["batch_id" => $batchId, "textlocal_id" => null]);
        return $model->update($payload);
    }

    /**
     * Update by Batch Id And Status
     *
     * @param int $batchId
     * @param string $status
     * @param array $payload
     * @return int
     */
    public function updateByBatchIdAndStatus(int $batchId, string $status, array $payload): int
    {
        $model = SmsLog::with([])->where(["batch_id" => $batchId, "status" => $status]);
        return $model->update($payload);
    }
}

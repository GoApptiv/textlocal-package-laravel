<?php

namespace GoApptiv\TextLocal\Repositories\Sms;

use GoApptiv\TextLocal\Repositories\BaseRepositoryInterface;

interface SmsLogRepositoryInterface extends BaseRepositoryInterface
{
    /**
     * Bulk Store
     *
     * @param array $payload
     * @return bool
     */
    public function bulkStore(array $payload): bool;

    /**
     * Update Status by Batch Id
     *
     * @param int $batchId
     * @param string $status
     * @return int
     */
    public function updateStatusByBatchId(int $batchId, string $status): int;

    /**
     * Update by Batch Id and Mobile
     *
     * @param int $batchId
     * @param string $mobile
     * @param array $payload
     * @return int
     */
    public function updateByBatchIdAndMobile(int $batchId, string $mobile, array $payload): int;

    /**
     * Update by Batch Id Where textlocal_id is null
     *
     * @param int $batchId
     * @param array $payload
     * @return int
     */
    public function updateByBatchIdWhereTextLocalIdIsNull(int $batchId, array $payload): int;
}

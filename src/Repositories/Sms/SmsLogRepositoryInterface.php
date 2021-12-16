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
     * Update Status by Bulk Id
     *
     * @param int $bulkId
     * @param string $status
     * @return int
     */
    public function updateStatusByBulkId(int $bulkId, string $status): int;

    /**
     * Update by Bulk Id and Mobile
     *
     * @param int $bulkId
     * @param string $mobile
     * @param array $payload
     * @return int
     */
    public function updateByBulkIdAndMobile(int $bulkId, string $mobile, array $payload): int;

    /**
     * Update by Bulk Id Where textlocal_id is null
     *
     * @param int $bulkId
     * @param array $payload
     * @return int
     */
    public function updateByBulkIdWhereTextLocalIdIsNull(int $bulkId, array $payload): int;
}

<?php

namespace GoApptiv\TextLocal\Repositories\MySql\Account;

use GoApptiv\TextLocal\Models\Account\Account;
use GoApptiv\TextLocal\Repositories\Account\AccountRepositoryInterface;
use GoApptiv\TextLocal\Repositories\MySql\MySqlBaseRepository;

class AccountRepository extends MySqlBaseRepository implements AccountRepositoryInterface
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
    public function __construct(Account $model)
    {
        $this->model = $model;
    }
}

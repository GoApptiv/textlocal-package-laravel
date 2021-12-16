<?php

namespace GoApptiv\TextLocal\Providers;

use GoApptiv\TextLocal\Repositories\Account\AccountRepositoryInterface;
use GoApptiv\TextLocal\Repositories\BaseRepositoryInterface;
use GoApptiv\TextLocal\Repositories\MySql\Account\AccountRepository;
use GoApptiv\TextLocal\Repositories\MySql\MySqlBaseRepository;
use GoApptiv\TextLocal\Repositories\MySql\Sms\SmsBatchLogRepository;
use GoApptiv\TextLocal\Repositories\MySql\Sms\SmsLogRepository;
use GoApptiv\TextLocal\Repositories\Sms\SmsBatchLogRepositoryInterface;
use GoApptiv\TextLocal\Repositories\Sms\SmsLogRepositoryInterface;
use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register(): void
    {
        $toBind = [
            BaseRepositoryInterface::class  => MySqlBaseRepository::class,
            AccountRepositoryInterface::class => AccountRepository::class,
            SmsLogRepositoryInterface::class => SmsLogRepository::class,
            SmsBatchLogRepositoryInterface::class => SmsBatchLogRepository::class
        ];

        foreach ($toBind as $interface => $implementation) {
            $this->app->bind($interface, $implementation);
        }
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}

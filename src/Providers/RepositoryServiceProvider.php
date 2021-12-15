<?php

namespace GoApptiv\TextLocal\Providers;

use GoApptiv\TextLocal\Repositories\BaseRepositoryInterface;
use GoApptiv\TextLocal\Repositories\MySql\MySqlBaseRepository;
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
            BaseRepositoryInterface::class  => MySqlBaseRepository::class
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

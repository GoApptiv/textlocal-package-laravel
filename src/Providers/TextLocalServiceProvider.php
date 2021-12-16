<?php

namespace GoApptiv\TextLocal\Providers;

use GoApptiv\TextLocal\Console\Commands\AddAccountCommand;
use GoApptiv\TextLocal\Console\Commands\ReplaceApiKeyCommand;
use GoApptiv\TextLocal\Repositories\Account\AccountRepositoryInterface;
use GoApptiv\TextLocal\Repositories\Sms\SmsBatchLogRepositoryInterface;
use GoApptiv\TextLocal\Repositories\Sms\SmsLogRepositoryInterface;
use GoApptiv\TextLocal\Services\TextLocal\TextLocalService;
use Illuminate\Support\ServiceProvider;

class TextLocalServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publishes the migration files
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'textlocal-migrations');

        if ($this->app->runningInConsole()) {
            $this->commands([
                AddAccountCommand::class,
                ReplaceApiKeyCommand::class
            ]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        $this->app->singleton('goapptiv-textlocal', function ($app) {
            return new TextLocalService(
                $app->make(AccountRepositoryInterface::class),
                $app->make(SmsLogRepositoryInterface::class),
                $app->make(SmsBatchLogRepositoryInterface::class),
            );
        });
    }
}

<?php

namespace GoApptiv\TextLocal\Providers;

use GoApptiv\TextLocal\Console\Commands\AddAccountCommand;
use GoApptiv\TextLocal\Console\Commands\ReplaceApiKeyCommand;
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
    }
}

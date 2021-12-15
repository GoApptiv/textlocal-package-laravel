<?php

namespace GoApptiv\TextLocal\Providers;

use Illuminate\Support\ServiceProvider;

class TextLocalServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Publishes the migration files
        $this->publishes([
            __DIR__ . '/../../database/migrations/' => database_path('migrations'),
        ], 'textlocal-migrations');
    }

    /**
     * Register the application services.
     */
    public function register()
    {
    }
}

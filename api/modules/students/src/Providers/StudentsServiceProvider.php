<?php

namespace Tamani\Students\Providers;

use Illuminate\Support\ServiceProvider;

class StudentsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'students');
        $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');
    }
}

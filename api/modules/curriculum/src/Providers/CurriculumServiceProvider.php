<?php

namespace Tamani\Curriculum\Providers;

use Illuminate\Support\ServiceProvider;

class CurriculumServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
//        $this->loadRoutesFrom(__DIR__ . '/../routes/terminal.php');
        $this->loadRoutesFrom(__DIR__ . '/../routes/admin.php');
//        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'rfid_terminal');
    }
}

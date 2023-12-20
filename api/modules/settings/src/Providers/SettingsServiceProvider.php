<?php
namespace Tamani\Settings\Providers;

use Illuminate\Support\ServiceProvider;

class SettingsServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
//        $this->mergeConfigFrom(__DIR__.'/../../config/config.php', 'sms-service');
        $this->loadRoutesFrom(__DIR__.'/../routes/admin.php');
    }
}

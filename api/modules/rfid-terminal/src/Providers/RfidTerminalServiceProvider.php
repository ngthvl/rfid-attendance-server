<?php

namespace Tamani\RfidTerminal\Providers;

use Illuminate\Support\ServiceProvider;

class RfidTerminalServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any package services.
     */
    public function boot(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../../database/migrations');
        $this->loadRoutesFrom(__DIR__ . '/../routes.php');
    }
}

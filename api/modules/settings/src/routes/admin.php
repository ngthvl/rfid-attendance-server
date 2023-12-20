<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:admin'])->prefix('api/v1/admin/settings')->group(function(){
    Route::get('/', [\Tamani\Settings\Http\Controllers\Admin\SettingsController::class, 'index']);
    Route::post('/', [\Tamani\Settings\Http\Controllers\Admin\SettingsController::class, 'store']);
});

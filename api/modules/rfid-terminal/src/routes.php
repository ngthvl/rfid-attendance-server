<?php

use Illuminate\Support\Facades\Route;

// api/v1
Route::middleware('api')->prefix('api/v1/')->group(function(){

    Route::post('auth/device', [\Tamani\RfidTerminal\Http\Controllers\V1\AuthController::class, 'store']);

    Route::middleware('auth:admin')->group(function(){
        Route::get('devices', [\Tamani\RfidTerminal\Http\Controllers\V1\AuthController::class, 'index']);
        Route::get('devices/{id}', [\Tamani\RfidTerminal\Http\Controllers\V1\AuthController::class, 'show']);
    });
});

<?php

use Illuminate\Support\Facades\Route;

// terminal Routes
Route::middleware('api')->prefix('api/v1/terminal')->group(function(){

    Route::post('auth', [\Tamani\RfidTerminal\Http\Controllers\V1\AuthController::class, 'store']);

    Route::middleware('auth:rfidTerminal')->group(function(){
        Route::post('auth/refresh', [\Tamani\RfidTerminal\Http\Controllers\V1\AuthController::class, 'refresh']);
        Route::post('attendance', [\Tamani\RfidTerminal\Http\Controllers\V1\AttendanceController::class, 'store']);
    });
});


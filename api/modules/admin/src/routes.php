<?php

use Illuminate\Support\Facades\Route;

// api/v1
Route::middleware('api')->prefix('api/v1/')->group(function(){

    Route::post('auth/login', [\Tamani\Admin\Http\Controllers\V1\Auth\AuthController::class, 'store']);

    Route::middleware('auth:admin')->group(function(){
        Route::get('admin', [\Tamani\Admin\Http\Controllers\V1\Admin\AccountsController::class, 'index']);
        Route::get('admin/{id}', [\Tamani\Admin\Http\Controllers\V1\Admin\AccountsController::class, 'show']);
    });
});

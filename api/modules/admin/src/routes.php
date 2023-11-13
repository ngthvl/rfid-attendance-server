<?php

use Illuminate\Support\Facades\Route;

// api/v1
Route::middleware('api')->prefix('api/v1/admin')->group(function(){

    Route::post('auth', [\Tamani\Admin\Http\Controllers\V1\Auth\AuthController::class, 'store']);

    Route::middleware('auth:admin')->group(function(){
        Route::get('admins', [\Tamani\Admin\Http\Controllers\V1\Admin\AccountsController::class, 'index']);
        Route::get('admins/{id}', [\Tamani\Admin\Http\Controllers\V1\Admin\AccountsController::class, 'show']);
    });
});

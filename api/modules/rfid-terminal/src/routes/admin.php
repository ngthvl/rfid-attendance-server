<?php

use Illuminate\Support\Facades\Route;

// admin routes
Route::middleware(['api', 'auth:admin'])->prefix('api/v1/admin/terminals')->group(function(){
    Route::get('/', [\Tamani\RfidTerminal\Http\Controllers\Admin\RfidTerminalController::class, 'index']);
    Route::get('/{id}', [\Tamani\RfidTerminal\Http\Controllers\Admin\RfidTerminalController::class, 'show']);
    Route::post('/{id}/authorize', [\Tamani\RfidTerminal\Http\Controllers\Admin\RfidTerminalController::class, 'authorizeDevice']);
});

// admin routes
Route::middleware(['api', 'auth:admin'])->prefix('api/v1/admin/rfid-tags')->group(function(){
    Route::post('/check', [\Tamani\RfidTerminal\Http\Controllers\Admin\TagAllocationController::class, 'checkAllocation']);
    Route::post('/allocate', [\Tamani\RfidTerminal\Http\Controllers\Admin\TagAllocationController::class, 'allocateTag']);
});

// admin routes
Route::middleware(['api', 'auth:admin'])->prefix('api/v1/admin/rfid-detections')->group(function(){
    Route::post('/', [\Tamani\RfidTerminal\Http\Controllers\Admin\RfidTagDetectionController::class, 'index']);
});

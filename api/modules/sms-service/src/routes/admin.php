<?php
use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:admin'])->prefix('api/v1/admin')->group(function(){
    Route::get('phonebook', [\Tamani\Sms\Http\Controllers\SmsController::class, 'phonebook']);
    Route::get('phonebook/{phonebookId}/messages', [\Tamani\Sms\Http\Controllers\SmsController::class, 'showByPbId']);
});

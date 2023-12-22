<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('v1/webhooks/sms-server-webhook/{tag}', function(Request $request, string $tag){
    \Illuminate\Support\Facades\Log::info($tag, $request->all());
})->name('sms-server-wh');

Route::middleware(['auth'])->group(function(){
    Route::get('v1/file/upload', [\App\Http\Controllers\FileUploadController::class, 'generateUploadLink']);
    Route::post('v1/file/upload', [\App\Http\Controllers\FileUploadController::class, 'upload'])->middleware(['signed', 'throttle'])->name('file.upload');
});

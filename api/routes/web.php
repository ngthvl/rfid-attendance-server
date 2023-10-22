<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::middleware('api')->get('/', function () {
    $user = \Tamani\Admin\Models\Admin::factory()->create();
    $user->save();
    $accessToken = $user->createToken('personal')->accessToken;
//    $user->createToken('access')
    return $accessToken;
});

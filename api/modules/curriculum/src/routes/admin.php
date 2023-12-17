<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:admin'])->prefix('api/v1/admin/education-levels')->group(function(){
    Route::get('/', [\Tamani\Curriculum\Http\Controllers\V1\EducationLevelController::class, 'index']);
    Route::post('/', [\Tamani\Curriculum\Http\Controllers\V1\EducationLevelController::class, 'store']);
});

Route::middleware(['api', 'auth:admin'])->prefix('api/v1/admin/sections')->group(function(){
    Route::get('/', [\Tamani\Curriculum\Http\Controllers\V1\SectionController::class, 'index']);
    Route::post('/', [\Tamani\Curriculum\Http\Controllers\V1\SectionController::class, 'store']);
});

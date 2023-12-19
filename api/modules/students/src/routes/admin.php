<?php

use Illuminate\Support\Facades\Route;

Route::middleware(['api', 'auth:admin'])->prefix('api/v1/admin')->group(function(){
    Route::get('students', [\Tamani\Students\Http\Controllers\StudentAccountController::class, 'index']);
    Route::post('students', [\Tamani\Students\Http\Controllers\StudentAccountController::class, 'store']);
    Route::post('students/import', [\Tamani\Students\Http\Controllers\StudentAccountController::class, 'importStudentsFromCsv']);
    Route::delete('students/import', [\Tamani\Students\Http\Controllers\StudentAccountController::class, 'undoStudentCsvImport']);
    Route::get('students/list-imports/{directory}', [\Tamani\Students\Http\Controllers\StudentAccountController::class, 'listImports']);
    Route::get('students/attendance', [\Tamani\Students\Http\Controllers\StudentAttendanceController::class, 'index']);
    Route::get('students/{id}', [\Tamani\Students\Http\Controllers\StudentAccountController::class, 'show']);
});

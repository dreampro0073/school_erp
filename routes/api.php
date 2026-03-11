<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StudentController;

// Route::apiResource('students', StudentController::class);
Route::prefix('students')
    ->controller(StudentController::class)
    ->group(function () {
        Route::any('/init', 'initStudents');
    }
);

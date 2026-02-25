<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\ServicesController;

// Route::apiResource('students', StudentController::class);
Route::prefix('students')
    ->controller(StudentController::class)
    ->group(function () {
        Route::post('/init', 'initStudents');
    }
);

Route::prefix('services')
    ->controller(ServicesController::class)
    ->group(function () {
        Route::post('/init', 'initServices');
        Route::post('/store', 'storeService');
    }
);

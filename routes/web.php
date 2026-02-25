<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;

Route::get('/', [UserController::class,'login'])->name("login");
Route::post('/login', [UserController::class,'postLogin']);

Route::prefix('students')->middleware(['auth'])
    ->controller(StudentController::class)
    ->group(function () {
        Route::get('/', 'index');
    }
);

<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\StandardsController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\FeeTypesController;

Route::get('/', [UserController::class,'login'])->name("login");
Route::post('/login', [UserController::class,'postLogin']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::prefix('students')->middleware(['auth'])
    ->controller(StudentController::class)
    ->group(function () {
        Route::get('/', 'index');
    }
);

Route::prefix('super-admin')->middleware(['auth'])
    ->controller(SuperAdminController::class)
    ->group(function () {
        Route::get('/dashboard', 'dashboard');
        Route::get('/schools/create', 'createSchoolPage')->name('super-admin.schools.create-page');
        Route::get('/schools/{id}/edit', 'editSchoolPage')->name('super-admin.schools.edit-page');
        Route::get('/schools/{id}/services', 'schoolServicesPage')->name('super-admin.schools.services');
        Route::post('/schools/{id}/services', 'saveSchoolServices')->name('super-admin.schools.services.save');
        Route::get('/users', 'usersList')->name('super-admin.users');
        Route::get('/users/{type}', 'usersByType')->name('super-admin.users.type');
        Route::post('/users/{id}/status', 'updateUserStatus')->name('super-admin.users.status');
        Route::post('/schools', 'createSchool')->name('super-admin.schools.create');
        Route::post('/schools/{id}', 'updateSchool')->name('super-admin.schools.update');
    }
);

Route::prefix('super-admin')->middleware(['auth'])
    ->controller(ServicesController::class)
    ->group(function () {
            Route::get('/services', 'index')->name('super-admin.services.index');
    }
);

Route::prefix('super-admin')->middleware(['auth'])
    ->controller(StandardsController::class)
    ->group(function () {
        Route::get('/standards', 'index')->name('super-admin.standards.index');
    }
);

Route::prefix('super-admin')->middleware(['auth'])
    ->controller(SectionsController::class)
    ->group(function () {
        Route::get('/sections', 'index')->name('super-admin.sections.index');
    }
);

Route::prefix('super-admin')->middleware(['auth'])
    ->controller(SubjectsController::class)
    ->group(function () {  
        Route::get('/subjects', 'index')->name('super-admin.subjects.index');
    }
);

Route::prefix('super-admin')->middleware(['auth'])->controller(FeeTypesController::class)->group(function () {
        Route::get('/fee-types', 'index')->name('super-admin.fee-types.index');
    }
);

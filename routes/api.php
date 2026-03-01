<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\StudentController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\StandardsController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\FeeTypesController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\GuardianController;

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

Route::prefix('standards')
    ->controller(StandardsController::class)
    ->group(function () {
        Route::post('/init', 'initStandards');
        Route::post('/store', 'storeStandard');
        Route::post('/delete', 'deleteStandard');
    }
);

Route::prefix('sections')
    ->controller(SectionsController::class)
    ->group(function () {
        Route::post('/init', 'initSections');
        Route::post('/store', 'storeSection');
        Route::post('/delete', 'deleteSection');
    }
);

Route::prefix('subjects')
    ->controller(SubjectsController::class)
    ->group(function () {
        Route::post('/init', 'initSubjects');
        Route::post('/store', 'storeSubject');
        Route::post('/delete', 'deleteSubject');
    }
);

Route::prefix('fee-types')
    ->controller(FeeTypesController::class)
    ->group(function () {
        Route::post('/init', 'initFeeTypes');
        Route::post('/store', 'storeFeeType');
        Route::post('/delete', 'deleteFeeType');
    }
);

Route::prefix('dashboard')
    ->controller(SuperAdminController::class)
    ->group(function () {
        Route::post('/init', 'initDashboard');
    }
);

Route::prefix('admin/dashboard')
    ->controller(AdminController::class)
    ->group(function () {
        Route::post('/init', 'initDashboard');
    }
);

Route::prefix('admin')
    ->controller(AdminController::class)
    ->group(function () {
        Route::post('/students/init', 'initStudents');
        Route::post('/students/get', 'getStudent');
        Route::post('/students/store', 'storeStudent');
        Route::post('/students/status', 'updateStudentStatus');
        Route::post('/teachers/init', 'initTeachers');
        Route::post('/teachers/get', 'getTeacher');
        Route::post('/teachers/store', 'storeTeacher');
    }
);

Route::prefix('admin')
    ->controller(IncomeController::class)
    ->group(function () {
        Route::post('/incomes/init', 'initIncomes');
        Route::post('/incomes/store', 'storeIncome');
        Route::post('/income-entries/init', 'initIncomeEntries');
        Route::post('/income-entries/store', 'storeIncomeEntry');
        Route::post('/expenses/init', 'initExpenses');
        Route::post('/expenses/store', 'storeExpense');
        Route::post('/expense-entries/init', 'initExpenseEntries');
        Route::post('/expense-entries/store', 'storeExpenseEntry');
    }
);

Route::prefix('admin')
    ->controller(AttendanceController::class)
    ->group(function () {
        Route::post('/attendance/init', 'init');
        Route::post('/attendance/store', 'store');
        Route::post('/attendance/list', 'list');
    }
);

Route::prefix('teachers/dashboard')
    ->controller(TeacherController::class)
    ->group(function () {
        Route::post('/init', 'initDashboard');
    }
);

Route::prefix('gurdian/dashboard')
    ->controller(GuardianController::class)
    ->group(function () {
        Route::post('/init', 'initDashboard');
    }
);

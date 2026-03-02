<?php

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
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SalaryController;

// Grouped structure for middleware-based organization (no behavior change).
Route::middleware(['api-token-user'])->group(function () {
    Route::prefix('students')->controller(StudentController::class)->group(function () {
        Route::post('/init', 'initStudents');
    });

    Route::prefix('services')->controller(ServicesController::class)->group(function () {
        Route::post('/init', 'initServices');
        Route::post('/store', 'storeService');
    });

    Route::prefix('standards')->controller(StandardsController::class)->group(function () {
        Route::post('/init', 'initStandards');
        Route::post('/store', 'storeStandard');
        Route::post('/delete', 'deleteStandard');
    });

    Route::prefix('sections')->controller(SectionsController::class)->group(function () {
        Route::post('/init', 'initSections');
        Route::post('/store', 'storeSection');
        Route::post('/delete', 'deleteSection');
    });

    Route::prefix('subjects')->controller(SubjectsController::class)->group(function () {
        Route::post('/init', 'initSubjects');
        Route::post('/store', 'storeSubject');
        Route::post('/delete', 'deleteSubject');
    });

    Route::prefix('fee-types')->controller(FeeTypesController::class)->group(function () {
        Route::post('/init', 'initFeeTypes');
        Route::post('/store', 'storeFeeType');
        Route::post('/delete', 'deleteFeeType');
    });

    Route::prefix('dashboard')->controller(SuperAdminController::class)->group(function () {
        Route::post('/init', 'initDashboard');
    });

    Route::prefix('admin')->group(function () {
        Route::prefix('dashboard')->controller(AdminController::class)->group(function () {
            Route::post('/init', 'initDashboard');
        });

        Route::controller(AdminController::class)->group(function () {
            Route::post('/students/init', 'initStudents');
            Route::post('/students/get', 'getStudent');
            Route::post('/students/store', 'storeStudent');
            Route::post('/students/status', 'updateStudentStatus');
            Route::post('/teachers/init', 'initTeachers');
            Route::post('/teachers/get', 'getTeacher');
            Route::post('/teachers/store', 'storeTeacher');
        });

        Route::prefix('teacher-salary')->controller(SalaryController::class)->group(function () {
            Route::post('/profile/get', 'getTeacherSalaryProfile');
            Route::post('/profile/store', 'saveTeacherSalaryProfile');
            Route::post('/logs/init', 'initTeacherSalaryLogs');
            Route::post('/logs/store', 'storeTeacherSalaryLog');
        });

        Route::controller(IncomeController::class)->group(function () {
            Route::post('/incomes/init', 'initIncomes');
            Route::post('/incomes/store', 'storeIncome');
            Route::post('/income-entries/init', 'initIncomeEntries');
            Route::post('/income-entries/store', 'storeIncomeEntry');
            Route::post('/expenses/init', 'initExpenses');
            Route::post('/expenses/store', 'storeExpense');
            Route::post('/expense-entries/init', 'initExpenseEntries');
            Route::post('/expense-entries/store', 'storeExpenseEntry');
        });

        Route::controller(AttendanceController::class)->group(function () {
            Route::post('/attendance/init', 'init');
            Route::post('/attendance/store', 'store');
            Route::post('/attendance/list', 'list');
        });
    });

    Route::prefix('teachers')->group(function () {
        Route::prefix('dashboard')->controller(TeacherController::class)->group(function () {
            Route::post('/init', 'initDashboard');
        });

        Route::prefix('exam-marks')->controller(TeacherController::class)->group(function () {
            Route::post('/init', 'initExamMarks');
            Route::post('/store', 'storeExamMark');
            Route::post('/list', 'listExamMarks');
        });
    });

    Route::prefix('gurdian/dashboard')->controller(GuardianController::class)->group(function () {
        Route::post('/init', 'initDashboard');
    });

    Route::prefix('chat')->controller(ChatController::class)->group(function () {
        Route::post('/init', 'init');
        Route::post('/thread', 'thread');
        Route::post('/send', 'send');
    });
});

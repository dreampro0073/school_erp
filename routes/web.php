<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\StudentController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\ServicesController;
use App\Http\Controllers\StandardsController;
use App\Http\Controllers\SectionsController;
use App\Http\Controllers\SubjectsController;
use App\Http\Controllers\FeeTypesController;
use App\Http\Controllers\WorklogController;
use App\Http\Controllers\IncomeController;
use App\Http\Controllers\AttendanceController;
use App\Http\Controllers\TeacherController;
use App\Http\Controllers\GuardianController;
use App\Http\Controllers\ChatController;
use App\Http\Controllers\SchoolManagementController;
use App\Http\Controllers\AspirantController;

Route::get('/', function () {
    return view('front-end.index');
});
Route::get('/login', [UserController::class, 'login'])->name('login');
Route::get('/login/captcha', [UserController::class, 'captcha'])->name('login.captcha');
Route::post('/login', [UserController::class, 'postLogin']);
Route::get('/logout', [UserController::class, 'logout']);
// Route::get('/receipt/{payment_id?}', [StudentController::class, 'generateReceipt']);

Route::middleware(['auth'])->group(function () {
    Route::prefix('worklog')->controller(WorklogController::class)->group(function () {
        Route::get('/', 'index');
        Route::post('/edit', 'edit');
        Route::post('/store', 'store');
    });

    Route::prefix('settings')->controller(UserController::class)->group(function () {
        Route::get('/', 'settings');
        Route::post('/reset-password', 'updatePassword');
    });   

    Route::prefix('students')->controller(StudentController::class)->group(function () {
        Route::get('/', 'index');
    });

    Route::prefix('chat')->controller(ChatController::class)->group(function () {
        Route::get('/', 'index');
    });

    Route::middleware(['teacher'])->prefix('teachers')->controller(TeacherController::class)->group(function () {
        Route::get('/dashboard', 'dashboard');
        Route::get('/exam-marks', 'examMarksPage');
    });

    Route::middleware(['guardian'])->prefix('gurdian')->controller(GuardianController::class)->group(function () {
        Route::get('/dashboard', 'dashboard');

    });    

    Route::middleware(['aspirant'])->prefix('aspirant')->controller(AspirantController::class)->group(function () {
        Route::get('/dashboard', 'dashboard');
        Route::get('/subjects/index', 'subjectsIndex');
        Route::get('/subjects/{subjectId}/topics', 'topicsIndex');

    });

    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/profile/{user_id}', 'userProfilePage');
    });

    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'dashboard');

            Route::prefix('teachers')->controller(TeacherController::class)->group(function () {
                // Route::get('/', 'teachersIndex');
                // Route::get('/teachers/add/{teacher?}', 'addTeacherPage');

                Route::get('/', 'index');
                Route::get('/add/{teacher_token?}', 'addTeacherPage');
                Route::get('/profile/{teacher_token?}', 'teacherProfile');
            });

            Route::prefix('students')->controller(StudentController::class)->group(function () {
                Route::get('/', 'index');
                Route::get('/add/{student_token?}', 'addStudentPage');
                Route::get('/profile/{student_token?}', 'studentProfile');
                Route::get('/receipt/{payment_id?}', 'generateReceipt');
            });
        });

        Route::prefix('school')->controller(SchoolManagementController::class)->group(function () {
            Route::get('/index', 'index');
            Route::get('/class-manage/{id}', 'classManage');
        });

        // Route::controller(IncomeController::class)->group(function () {
        //     Route::get('/incomes', 'incomesPage');
        //     Route::get('/income-entries', 'incomeEntriesPage');
        //     Route::get('/expenses', 'expensesPage');
        //     Route::get('/expense-entries', 'expenseEntriesPage');
        // });

        Route::controller(AttendanceController::class)->group(function () {
            Route::get('/attendance', 'index');
        });
    });

    // *** HOLD ***

    // if(false){ 
        Route::middleware(['super-admin'])->prefix('super-admin')->group(function () {
            Route::controller(SuperAdminController::class)->group(function () {
                Route::get('/dashboard', 'dashboard');
                Route::get('/schools', 'usersList');
                Route::get('/teachers', 'usersList');
                Route::get('/students', 'usersList');
                Route::get('/parents', 'usersList');
                Route::get('/school/add/{id?}', 'addSchool');
                Route::get('/users/{type}', 'usersByType');
                // Route::get('/users/profile/{id}', 'userProfile');
                // Route::get('/schools/{id}/services', 'schoolServicesPage');
                // Route::post('/schools/{id}/services', 'saveSchoolServices');
                // Route::post('/users/{id}/status', 'updateUserStatus');
            });

            Route::controller(ServicesController::class)->group(function () {
                Route::get('/services', 'index');
            });

            Route::controller(StandardsController::class)->group(function () {
                Route::get('/standards', 'index');
            });

            Route::controller(SectionsController::class)->group(function () {
                Route::get('/sections', 'index');
            });

            Route::controller(SubjectsController::class)->group(function () {
                Route::get('/subjects', 'index');
            });

            Route::controller(FeeTypesController::class)->group(function () {
                Route::get('/fee-types', 'index');
            });
        });
    // }
});

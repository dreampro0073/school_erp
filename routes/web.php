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

Route::get('/', [UserController::class, 'login'])->name('login');
Route::get('/login/captcha', [UserController::class, 'captcha'])->name('login.captcha');
Route::post('/login', [UserController::class, 'postLogin']);
Route::post('/logout', [UserController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::prefix('worklog')->controller(WorklogController::class)->group(function () {
        Route::get('/', 'index')->name('worklog.index');
        Route::post('/store', 'store')->name('worklog.store');
    });

    Route::prefix('students')->controller(StudentController::class)->group(function () {
        Route::get('/', 'index');
    });

    Route::prefix('chat')->controller(ChatController::class)->group(function () {
        Route::get('/', 'index')->name('chat.index');
    });

    Route::middleware(['teacher'])->prefix('teachers')->controller(TeacherController::class)->group(function () {
        Route::get('/dashboard', 'dashboard');
        Route::get('/exam-marks', 'examMarksPage')->name('teachers.exam-marks.index');
    });

    Route::middleware(['guardian'])->prefix('gurdian')->controller(GuardianController::class)->group(function () {
        Route::get('/dashboard', 'dashboard');
    });

    Route::middleware(['admin'])->prefix('admin')->group(function () {
        Route::controller(AdminController::class)->group(function () {
            Route::get('/dashboard', 'dashboard');
            Route::get('/students', 'studentsIndex')->name('admin.students.index');
            Route::get('/students/add/{student?}', 'addStudentPage')->name('admin.students.add');
            Route::get('/students/profile/{student}', 'studentProfilePage')->name('admin.students.profile');
            Route::get('/teachers', 'teachersIndex')->name('admin.teachers.index');
            Route::get('/teachers/add/{teacher?}', 'addTeacherPage')->name('admin.teachers.add');
        });

        Route::controller(IncomeController::class)->group(function () {
            Route::get('/incomes', 'incomesPage')->name('admin.incomes.index');
            Route::get('/income-entries', 'incomeEntriesPage')->name('admin.income-entries.index');
            Route::get('/expenses', 'expensesPage')->name('admin.expenses.index');
            Route::get('/expense-entries', 'expenseEntriesPage')->name('admin.expense-entries.index');
        });

        Route::controller(AttendanceController::class)->group(function () {
            Route::get('/attendance', 'index')->name('admin.attendance.index');
        });
    });

    Route::middleware(['super-admin'])->prefix('super-admin')->group(function () {
        Route::controller(SuperAdminController::class)->group(function () {
            Route::get('/dashboard', 'dashboard');
            Route::get('/users/profile/{id}', 'userProfile')->name('super-admin.users.profile');
            Route::get('/schools/create', 'createSchoolPage')->name('super-admin.schools.create-page');
            Route::get('/schools/{id}/edit', 'editSchoolPage')->name('super-admin.schools.edit-page');
            Route::get('/schools/{id}/services', 'schoolServicesPage')->name('super-admin.schools.services');
            Route::post('/schools/{id}/services', 'saveSchoolServices')->name('super-admin.schools.services.save');
            Route::get('/users', 'usersList')->name('super-admin.users');
            Route::get('/users/{type}', 'usersByType')->name('super-admin.users.type');
            Route::post('/users/{id}/status', 'updateUserStatus')->name('super-admin.users.status');
            Route::post('/schools', 'createSchool')->name('super-admin.schools.create');
            Route::post('/schools/{id}', 'updateSchool')->name('super-admin.schools.update');
        });

        Route::controller(ServicesController::class)->group(function () {
            Route::get('/services', 'index')->name('super-admin.services.index');
        });

        Route::controller(StandardsController::class)->group(function () {
            Route::get('/standards', 'index')->name('super-admin.standards.index');
        });

        Route::controller(SectionsController::class)->group(function () {
            Route::get('/sections', 'index')->name('super-admin.sections.index');
        });

        Route::controller(SubjectsController::class)->group(function () {
            Route::get('/subjects', 'index')->name('super-admin.subjects.index');
        });

        Route::controller(FeeTypesController::class)->group(function () {
            Route::get('/fee-types', 'index')->name('super-admin.fee-types.index');
        });
    });
});

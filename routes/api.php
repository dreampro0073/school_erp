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
use App\Http\Controllers\WorklogController;
use App\Http\Controllers\SchoolManagementController;

// Grouped structure for middleware-based organization (no behavior change).
Route::middleware(['api-token-user'])->group(function () {

    // Route::prefix('students')->controller(StudentController::class)->group(function () {
    //     Route::post('/init', 'initStudents');
    // });

    Route::prefix('chat')->controller(ChatController::class)->group(function () {
        Route::post('/init', 'initChat');
        Route::post('/get-chat', 'getChat');
    });    

    Route::prefix('services')->controller(ServicesController::class)->group(function () {
        Route::post('/init', 'initServices');
        Route::post('/store', 'storeService');
    });

    Route::prefix('standards')->controller(StandardsController::class)->group(function () {
        Route::post('/init', 'initStandards');
        Route::post('/store', 'storeStandard');
    });

    Route::prefix('sections')->controller(SectionsController::class)->group(function () {
        Route::post('/init', 'initSections');
        Route::post('/store', 'storeSection');
    });

    Route::prefix('subjects')->controller(SubjectsController::class)->group(function () {
        Route::post('/init', 'initSubjects');
        Route::post('/store', 'storeSubject');
    });

    Route::prefix('fee-types')->controller(FeeTypesController::class)->group(function () {
        Route::post('/init', 'initFeeTypes');
        Route::post('/store', 'storeFeeType');
    });

    Route::prefix('super-admin')->group(function () {
        Route::prefix('dashboard')->controller(SuperAdminController::class)->group(function () {
            Route::post('/init', 'initDashboard');
        });

        Route::prefix('users')->controller(SuperAdminController::class)->group(function () {
            Route::post('/init', 'initUsers');
        });
    });


    Route::prefix('admin')->group(function () {
        Route::prefix('dashboard')->controller(AdminController::class)->group(function () {
            Route::post('/init', 'initDashboard');
        });
        Route::prefix('students')
            ->controller(StudentController::class)
            ->group(function () {
                Route::any('/init', 'initStudents');
                Route::post('/store', 'storeStudent');
                Route::post('/init-details', 'initDetails');
                Route::post('/get-profile-details', 'getProfileDetails');
                Route::post('/get-attendance', 'getAttendance');
                Route::post('/get-leaves', 'getLeaves');
                Route::post('/get-exams', 'getExams');
                Route::post('/get-fees', 'getFees');
            }
        );

        Route::prefix('teachers')
            ->controller(TeacherController::class)
            ->group(function () {
                Route::any('/init', 'initTeachers');
                Route::post('/store', 'storeTeacher');
                Route::post('/init-details', 'initDetails');
                Route::post('/get-profile-details', 'getProfileDetails');
                Route::post('/get-attendance', 'getAttendance');
                Route::post('/get-leaves', 'getLeaves');
                Route::post('/get-fees', 'getFees');
            }
        );

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

        Route::prefix('school')->controller(SchoolManagementController::class)->group(function () {
            Route::post('/init', 'initSchool');
            
            Route::post('/schedule', 'initSchedule');
            Route::post('/schedule-edit', 'editSchedule');
            Route::post('/schedule-store', 'scheduleStore');
            
            Route::post('/classes', 'initClasses');
            Route::post('/class-store', 'classStore');
            Route::post('/change-class-status', 'changeClassStatus');
            Route::post('/class-edit', 'editClass');
            Route::post('/class-delete', 'deleteClass');
            
            Route::post('/class-manage-init', 'classManageInit');
            
            Route::post('/exams', 'initExams');
            Route::post('/exams-edit', 'editExams');
            Route::post('/exams-store', 'examsStore');
            
            Route::post('/results', 'initResults');
            Route::post('/results-edit', 'editResults');
            Route::post('/results-store', 'resultsStore');
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

    Route::prefix('worklog')->controller(WorklogController::class)->group(function () {
        Route::post('/init', 'initWorkLog');
        Route::post('/edit', 'getDayData');
        Route::post('/store', 'store');
    });  

});

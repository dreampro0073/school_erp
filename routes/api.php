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
use App\Http\Controllers\AspirantController;

// Grouped structure for middleware-based organization (no behavior change).
// Route::middleware(['api-token-user'])->group(function () {

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
            Route::post('/uploadFile', 'uploadFile');
            
            Route::post('/init', 'initUsers');
            Route::post('/edit', 'editUsers');
            Route::post('/submit-users', 'submitUsers');
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
                Route::post('/init-details', 'viewDetails');
                Route::post('/get-profile-details', 'getProfileDetails');
                Route::post('/get-attendance', 'getAttendance');
                Route::post('/get-leaves', 'getLeaves');
                Route::post('/get-exams', 'getExams');
                Route::post('/get-fee-params', 'getFeeParams');
                Route::post('/get-fees', 'getFees');
                Route::post('/get-fee-subs', 'getFeeSubs');
                Route::post('/collect-fee', 'collectFee');
                Route::post('/uploadFile', 'uploadFile');
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
                Route::post('/delete', 'deleteTeacher');
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
            

            Route::post('/fee-row-edit', 'feeRowEdit');
            Route::post('/fee-row-store', 'feeRowStore');
            Route::post('/fee-row-delete', 'feeRowDelete');            

            Route::post('/sub-row-edit', 'subRowEdit');
            Route::post('/sub-row-store', 'subRowStore');
            Route::post('/sub-row-delete', 'subRowDelete');
        });

        Route::prefix('school/transport')->controller(SchoolManagementController::class)->group(function () {
            Route::post('/init', 'initTransport');
            Route::post('/edit', 'editTransport');
            Route::post('/change-status', 'changeTransportStatus');
            Route::post('/store', 'storeTransport');
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

    Route::prefix('aspirant/dashboard')->controller(AspirantController::class)->group(function () {
        Route::post('/init', 'initDashboard');
    });

    Route::prefix('aspirant/subjects')->controller(AspirantController::class)->group(function () {
        Route::post('/init', 'initSubjects');
    });

    Route::prefix('aspirant/topics')->controller(AspirantController::class)->group(function () {
        Route::post('/init', 'initTopics');
        Route::post('/store', 'storeTopic');
    });

    Route::prefix('aspirant/questions')->controller(AspirantController::class)->group(function () {
        Route::post('/init', 'initQuestions');
        Route::post('/store', 'storeQuestion');
        Route::post('/upload-image', 'uploadQuestionImage');
    });

    Route::prefix('aspirant/practice')->controller(AspirantController::class)->group(function () {
        Route::post('/init', 'initPractice');
        Route::post('/random-question', 'randomQuestion');
    });

    Route::controller(AspirantController::class)->group(function () {
        Route::get('/subjects', 'examSubjects');
        Route::post('/start-exam', 'startExam');
        Route::get('/get-questions', 'getQuestions');
        Route::post('/save-answer', 'saveAnswer');
        Route::post('/submit-exam', 'submitExam');
        Route::get('/result', 'result');
        Route::get('/answer-key', 'answerKey');
    });

    Route::prefix('worklog')->controller(WorklogController::class)->group(function () {
        Route::post('/init', 'initWorkLog');
        Route::post('/edit', 'getDayData');
        Route::post('/store', 'store');
    });  

// });

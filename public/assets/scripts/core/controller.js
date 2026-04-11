// function confirmAction(title, text, onConfirm) {
//     if (typeof window.Swal !== 'undefined' && typeof window.Swal.fire === 'function') {
//         window.Swal.fire({
//             title: title || 'Are you sure?',
//             text: text || 'Please confirm this action.',
//             icon: 'warning',
//             showCancelButton: true,
//             confirmButtonText: 'Yes',
//             cancelButtonText: 'Cancel'
//         }).then(function(result) {
//             if (result.isConfirmed && typeof onConfirm === 'function') {
//                 onConfirm();
//             }
//         });
//         return;
//     }

//     if (typeof window.swal === 'function') {
//         window.swal({
//             title: title || 'Are you sure?',
//             text: text || 'Please confirm this action.',
//             type: 'warning',
//             showCancelButton: true,
//             confirmButtonText: 'Yes',
//             cancelButtonText: 'Cancel'
//         }, function(isConfirm) {
//             if (isConfirm && typeof onConfirm === 'function') {
//                 onConfirm();
//             }
//         });
//         return;
//     }

//     if (window.confirm(text || 'Please confirm this action.') && typeof onConfirm === 'function') {
//         onConfirm();
//     }
// }


// *** Super Admin ***
app.controller('suparAdminDashboardCtrl', function($scope , DBService){
    $scope.cards = [];

    $scope.init = function() {
        DBService.postCall({}, '/api/super-admin/dashboard/init').then(function(data) {
            
        });
    };
});


app.controller('superAdminUsersCtrl', function($scope , DBService){
    $scope.loading = true;
    $scope.type = "";
    $scope.dataSet = [];
    // $scope.today = '';
    // $scope.children = [];

    $scope.init = function() {
        DBService.postCall({type : $scope.type}, '/api/super-admin/users/init').then(function(res) {
            if(res.success){
                data = res.data;
                console.log(data);
                $scope.dataSet = data.dataSet;
            }
        });
    };

    
});

// *** Doubt ***
// app.controller('dashboardCtrl', function($scope , DBService){
//     $scope.cards = [];

//     $scope.init = function() {
//         DBService.postCall({}, '/api/dashboard/init').then(function(data) {
            
//         });
//     };
// });

// *** attendanceCtrl ***

app.controller('attendanceCtrl', function($scope , DBService){

    $scope.saving = false;
    $scope.statuses = [];
    $scope.defaultStatus = '';
    $scope.allItems = [];
    $scope.attendanceItems = [];
    $scope.historyRows = [];

    $scope.filters = {
        type: 'student',
        date: today,
        search: ''
    };

    $scope.historyFilter = {
        type: '',
        from_date: '',
        to_date: ''
    };

    $scope.init = function() {
        $scope.loadAttendance();
        $scope.loadHistory();
    };

    $scope.applyListFilter = function() {

    };

    $scope.resetSearch = function() {
        $scope.filters.search = '';
        $scope.applyListFilter();
    };

    $scope.loadAttendance = function() {
        DBService.postCall({ }, '/api/admin/attendance/init').then(function(data) {
            if (data.success) {
            }
            $scope.applyListFilter();
        });
    };

    $scope.saveAttendance = function() {

        $scope.saving = true;
        DBService.postCall({}, '/api/admin/attendance/store').then(function(data) {
           if (data.success) {
            } 
        });
    };

    $scope.loadHistory = function() {
        DBService.postCall({ }, '/api/admin/attendance/list').then(function(data) {
            if (data.success) {

            }
        });
    };
});

// *** feeTypesCtrl ***
app.controller('feeTypesCtrl', function($scope , DBService){
    $scope.feeTypes = [];
    $scope.processing = false;
    $scope.formData = {
        id: '',
        name: '',
        status: '0'
    };

    $scope.init = function() {
        DBService.postCall({}, '/api/fee-types/init').then(function(data) {
            if (data.success) {
                $scope.feeTypes = data.fee_types;
            }
        });
    };

    $scope.openAddModal = function() {
        $scope.formData = {id: '', name: '', status: '0'};
        $('#feeTypeModal').modal('show');
    };

    $scope.openEditModal = function(item) {
        $scope.formData = item;
        $('#feeTypeModal').modal('show');
    };

    $scope.saveFeeType = function() {
        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/fee-types/store').then(function(data) {
            $scope.processing = false;
            if (data.success) {
                $('#feeTypeModal').modal('hide');
                $scope.init();
                alert(data.message || 'Saved successfully.');
            } else {
                alert((data.message) ? data.message : 'Unable to save fee type.');
            }
        });
    };
});

// *** sectionsCtrl ***
app.controller('sectionsCtrl', function($scope , DBService){
    $scope.sections = [];
    $scope.processing = false;
    $scope.formData = {
        id: '',
        name: '',
        status:'0'
    };

    $scope.init = function() {
        DBService.postCall({}, '/api/sections/init').then(function(data) {
            if (data.success) {
                $scope.sections = data.sections ;
            }
        });
    };

    $scope.openAddModal = function() {
        $scope.formData = { id: '', name: '', status: '0' };
        $('#sectionModal').modal('show');
    };

    $scope.openEditModal = function(item) {
        $scope.formData = item;
        $('#sectionModal').modal('show');
    };

    $scope.saveSection = function() {
        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/sections/store').then(function(data) {
            $scope.processing = false;
            if (data.success) {
                $('#sectionModal').modal('hide');
                $scope.init();
                alert(data.message || 'Saved successfully.');
            } else {
                alert((data.message) ? data.message : 'Unable to save section.');
            }
        });
    };
});

// *** servicesCtrl ***
app.controller('servicesCtrl', function($scope , DBService){
    $scope.services = [];
    $scope.processing = false;
    $scope.formData = {
        id: '',
        name: '',
        status: '0'
    };

    $scope.init = function() {
        DBService.postCall({}, '/api/services/init').then(function(data) {
            if (data.success) {
                $scope.services = data.services ;
            }
        });
    };

    $scope.openAddModal = function() {
        $scope.formData = { id: '', name: '', status: '0' };
        $('#serviceModal').modal('show');
    };

    $scope.openEditModal = function(item) {
        $scope.formData = item;
        $('#serviceModal').modal('show');
    };

    $scope.saveService = function() {
        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/services/store').then(function(data) {
            $scope.processing = false;
            if (data.success) {
                $('#serviceModal').modal('hide');
                $scope.init();
                alert(data.message || 'Saved successfully.');
            } else {
                alert((data.message) ? data.message : 'Unable to save service.');
            }
        });
    };
});

// *** standardsCtrl ***
app.controller('standardsCtrl', function($scope , DBService){
    $scope.standards = [];
    $scope.processing = false;
    $scope.formData = {
        id: '',
        name: '',
        status: '0'
    };

    $scope.init = function() {
        DBService.postCall({}, '/api/standards/init').then(function(data) {
            if (data.success) {
                $scope.standards = data.standards ;
            }
        });
    };

    $scope.openAddModal = function() {
        $scope.formData = { id: '', name: '', status: '0' };
        $('#standardModal').modal('show');
    };

    $scope.openEditModal = function(item) {
        $scope.formData = item;
        $('#standardModal').modal('show');
    };

    $scope.saveStandard = function() {

        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/standards/store').then(function(data) {
            $scope.processing = false;
            if (data.success) {
                $('#standardModal').modal('hide');
                $scope.init();
                alert(data.message || 'Saved successfully.');
            } else {
                alert((data.message) ? data.message : 'Unable to save standard.');
            }
        });
    };

});

// *** subjectsCtrl ***
app.controller('subjectsCtrl', function($scope , DBService){
    $scope.subjects = [];
    $scope.processing = false;
    $scope.formData = {
        id: '',
        name: '',
        status: '0'
    };

    $scope.init = function() {
        DBService.postCall({}, '/api/subjects/init').then(function(data) {
            if (data.success) {
                $scope.subjects = data.subjects ;
            }
        });
    };

    $scope.openAddModal = function() {
        $scope.formData = { id: '', name: '', status: '0'};
        $('#subjectModal').modal('show');
    };

    $scope.openEditModal = function(item) {
        $scope.formData = {
            id: item.id,
            name: item.name,
            status: (item.status == 1 ? '1' : '0')
        };
        $('#subjectModal').modal('show');
    };

    $scope.saveSubject = function() {

        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/subjects/store').then(function(data) {
            $scope.processing = false;
            if (data.success) {
                $('#subjectModal').modal('hide');
                $scope.init();
                alert(data.message || 'Saved successfully.');
            } else {
                alert((data.message) ? data.message : 'Unable to save subject.');
            }
        });
    };

});

// *** studentCtrl ***
// app.controller('studentCtrl', function($scope , DBService){
//     $scope.loading = false;
//     $scope.allStudents = [];
//     $scope.students = [];
//     $scope.baseUrl = base_url;
//     $scope.filters = {
//         search: '',
//         gender: '',
//         status: ''
//     };

//     $scope.applyFilters = function() {
//         var search = ($scope.filters.search || '').toLowerCase().trim();
//         var gender = ($scope.filters.gender || '').toLowerCase();
//         var status = $scope.filters.status;

//         $scope.students = ($scope.allStudents || []).filter(function(item) {
//             var fullName = ((item.first_name || item.name || '') + ' ' + (item.last_name || '')).toLowerCase();
//             var admissionNo = String(item.admission_no || '').toLowerCase();
//             var mobile = String(item.mobile || '').toLowerCase();
//             var email = String(item.email || '').toLowerCase();
//             var itemGender = String(item.gender || '').toLowerCase();
//             var itemStatus = (item.active === 0 || item.active === '0') ? '0' : '1';

//             var matchesSearch = !search
//                 || fullName.indexOf(search) !== -1
//                 || admissionNo.indexOf(search) !== -1
//                 || mobile.indexOf(search) !== -1
//                 || email.indexOf(search) !== -1;
//             var matchesGender = !gender || itemGender === gender;
//             var matchesStatus = status === '' || itemStatus === status;

//             return matchesSearch && matchesGender && matchesStatus;
//         });
//     };

//     $scope.resetFilters = function() {
//         $scope.filters = {
//             search: '',
//             gender: '',
//             status: ''
//         };
//         $scope.applyFilters();
//     };

//     $scope.init = function() {
//         $scope.loading = true;
//         DBService.postCall({}, '/api/admin/students/init').then(function(data) {
//             if (data.success) {
//                 $scope.allStudents = data.students ;
//             } else {
//                 $scope.allStudents = [];
//                 $scope.students = [];
//             }
//             $scope.applyFilters();
//             $scope.loading = false;
//         });
//     };

//     $scope.toggleStatus = function(student, nextStatus) {
//         if (!student || !student.enc_id) {
//             return;
//         }

//         var actionLabel = nextStatus === 1 ? 'activate' : 'deactivate';
//         confirmAction('Confirm Status Change', 'Are you sure you want to ' + actionLabel + ' this student?', function() {
//             DBService.postCall({
//                 enc_id: student.enc_id,
//                 active: String(nextStatus)
//             }, '/api/admin/students/status').then(function(data) {
//                 alert((data.message) ? data.message : 'Status update failed.');
//                 if (data.success) {
//                     $scope.init();
//                 }
//             });
//         });
//     };
// });


// app.controller('studentCtrl', function($scope , $http, $timeout , DBService, Upload) {

//     $scope.loading = false;

//     $scope.students = [];

//     $scope.currentPage = 1;
//     $scope.totalPages = 1;
//     $scope.totalRecords = 0;

//     $scope.filter = {
//         page:1,
//         search:"",
//         limit:10,
//     };
//     let searchTimeout = null;

    
//     $scope.changePage = function(page){
//         $scope.init(page);
//     };
//     $scope.onSearch = function(){

//         if(searchTimeout){
//             $timeout.cancel(searchTimeout);
//         }

//         searchTimeout = $timeout(function(){

//             $scope.init(1);

//         },400); // 400ms delay

//     }
//     $scope.init = function(page = 1){
//         $scope.filter.page = page;
//         $scope.loading = true;

//         DBService.postCall($scope.filter,'/api/admin/students/init')
//         .then(function(res){
//             if(res.success){
//                 $scope.students = res.data.data;
//                 $scope.currentPage = res.data.current_page;
//                 $scope.totalPages = res.data.last_page;
//                 $scope.totalRecords = res.data.total;

//             }
//             $scope.loading = false;
//         });
//     }
// });

// app.controller('addStudentCtrl', function($scope , DBService){
//     $scope.processing = false;
//     $scope.formData = {
//         enc_id: '',
//         admission_no: '',
//         first_name: '',
//         last_name: '',
//         dob: '',
//         gender: '',
//         mobile: '',
//         email: '',
//         address: '',
//         aadhar_no: '',
//         parent_name: '',
//         parent_mobile: '',
//         parent_email: '',
//         parent_address: '',
//         parent_aadhar_no: '',
//         document_type: 'Aadhar',
//         document_no: '',
//         active: '1'
//     };

//     $scope.init = function() {

//         DBService.postCall({ }, '/api/admin/students/get').then(function(data) {

//         });
//     };

//     $scope.submit = function() {
//         $scope.processing = true;

//         DBService.postCall($scope.formData, '/api/admin/students/store').then(function(data) {
//             alert((data.message) ? data.message : 'Unable to save student.');
//             if (data.success) {
//                 window.location.href = base_url + '/admin/students';
//             }
//             $scope.processing = false;
//         }, function() {
//             $scope.processing = false;
//         });
//     };
// });

app.controller('studentProfileCtrl', function($scope , DBService){
    $scope.baseUrl = base_url;
    $scope.encId = '';
    $scope.student = {};
    $scope.parent = {};

    $scope.init = function() {

        DBService.postCall({ }, '/api/admin/students/get').then(function(data) {

        });
    };
});

// *** teacherCtrl ***
app.controller('teacherCtrl', function($scope, $timeout, DBService){
    $scope.loading = false;
    $scope.teachers = [];
    $scope.baseUrl = base_url;

    $scope.currentPage = 1;
    $scope.totalPages = 1;
    $scope.totalRecords = 0;

    $scope.filters = {
        page: 1,
        search: '',
        limit: 10,
        gender: '',
        status: ''
    };

    var searchTimeout = null;

    $scope.changePage = function(page) {
        $scope.init(page);
    };

    $scope.onSearch = function() {
        if (searchTimeout) {
            $timeout.cancel(searchTimeout);
        }

        searchTimeout = $timeout(function() {
            $scope.init(1);
        }, 400);
    };

    $scope.applyFilters = function() {
        $scope.init(1);
    };

    $scope.resetFilters = function() {
        $scope.filters = {
            page: 1,
            search: '',
            limit: 10,
            gender: '',
            status: ''
        };
        $scope.init(1);
    };

    $scope.init = function(page = 1) {
        $scope.filters.page = page;
        $scope.loading = true;

        DBService.postCall($scope.filters, '/api/admin/teachers/init')
            .then(function(data) {
                if (data.success) {
                    $scope.teachers = data.teachers.data;
                    $scope.currentPage = data.teachers.current_page;
                    $scope.totalPages = data.teachers.last_page;
                    $scope.totalRecords = data.teachers.total;
                } else {
                    $scope.teachers = [];
                    $scope.currentPage = 1;
                    $scope.totalPages = 1;
                    $scope.totalRecords = 0;
                }
                $scope.loading = false;
            });
    };
});

// *** adminDashboardCtrl ***
app.controller('adminDashboardCtrl', function($scope , DBService){
    // $scope.cards = [];
    $scope.attendance = [];
    $scope.teachers = {
        active_teachers: 0,
        inactive_teachers: 0,
        total_teachers: 0
    };

    $scope.students = {
        active_students: 0,
        inactive_students: 0,
        total_students: 0
    };


    $scope.init = function() {
        
        DBService.postCall({}, '/api/admin/dashboard/init').then(function(data) {
            if(data.success){
                console.log(data);
                $scope.teachers = data.teachers;
                $scope.students = data.students;
            }

        });
    };
});

// *** teacherDashboardCtrl ***
app.controller('teacherDashboardCtrl', function($scope , DBService){
    $scope.loading = true;
    $scope.today = '';
    $scope.teacherProfile = {};
    $scope.cards = [];
    $scope.studentAttendanceToday = [];
    $scope.teacherAttendanceToday = [];
    $scope.myAttendance = [];
    $scope.myAttendanceTotal = 0;
    $scope.recentStudentAttendance = [];

    $scope.init = function() {
        DBService.postCall({}, '/api/teachers/dashboard/init').then(function(data) {
            $scope.loading = false;
        }, function() {
            $scope.loading = false;
        });
    };
});

// *** guardianDashboardCtrl ***
app.controller('guardianDashboardCtrl', function($scope , DBService){
    $scope.loading = true;
    $scope.today = '';
    $scope.guardian = {};
    $scope.children = [];

    $scope.init = function() {
        DBService.postCall({}, '/api/gurdian/dashboard/init').then(function(data) {
            
        });
    };
});

// *** chatCtrl ***
app.controller('chatCtrl', function($scope , DBService, $timeout){
    $scope.users = [];
    $scope.chat_log = [];
    $scope.messages = [];
    $scope.selectedUser = null;
    $scope.searchUser = '';
    $scope.sending = false;
    $scope.loadingThread = false;
    $scope.draft = { message: '' };

    $scope.init = function() {
        DBService.postCall({}, '/api/chat/init').then(function(data) {
            $scope.chat_log = data.chat_log;
        });
    };

    $scope.selectUser = function(user) {
        DBService.postCall({user_id : user.user_id}, '/api/chat/get-chat').then(function(data) {
            $scope.chat = data.chat;
        });
    };

    // $scope.fetchThread = function() {
        
    //     DBService.postCall({ }, '/api/chat/thread').then(function(data) {

    //     });
    // };

    // $scope.sendMessage = function() {
        
    //     DBService.postCall({ }, '/api/chat/send').then(function(data) {
    //         $scope.sending = false;
    //         if (data.success) {
    //         } else {
    //             alert((data.message) ? data.message : 'Unable to send message.');
    //         }
    //     });
    // };

    // $scope.handleEnter = function($event) {
    //     if ($event.keyCode === 13 && !$event.shiftKey) {
    //         $event.preventDefault();
    //         $scope.sendMessage();
    //     }
    // };

    // $scope.scrollBottom = function() {
    //     $timeout(function() {
    //         var box = document.getElementById('chatThreadBox');
    //         if (box) {
    //             box.scrollTop = box.scrollHeight;
    //         }
    //     }, 80);
    // };
});

// *** examMarksCtrl ***
app.controller('examMarksCtrl', function($scope , DBService){
    function todayDate() {
        var d = new Date();
        var m = String(d.getMonth() + 1).padStart(2, '0');
        var day = String(d.getDate()).padStart(2, '0');
        return d.getFullYear() + '-' + m + '-' + day;
    }

    $scope.students = [];
    $scope.subjects = [];
    $scope.rows = [];
    $scope.saving = false;
    $scope.filters = {
        exam_name: '',
        student_id: '',
        subject_id: ''
    };
    $scope.formData = {};

    $scope.resetForm = function() {
        $scope.formData = {
            id: '',
            exam_name: '',
            exam_date: todayDate(),
            student_id: '',
            subject_id: '',
            total_marks: 100,
            obtained_marks: '',
            remark: ''
        };
    };

    $scope.init = function() {
        $scope.resetForm();
        DBService.postCall({}, '/api/teachers/exam-marks/init').then(function(data) {

            $scope.students = data.students ;
            $scope.subjects = data.subjects ;
            $scope.rows = data.rows ;
        });
    };

    $scope.loadRows = function() {
        DBService.postCall({}, '/api/teachers/exam-marks/list').then(function(data) {
            if (data.success) {
                $scope.rows = data.rows ;
            } else {
                $scope.rows = [];
            }
        });
    };

    $scope.resetFilters = function() {
        $scope.filters = { exam_name: '', student_id: '', subject_id: '' };
        $scope.loadRows();
    };

    $scope.editRow = function(row) {
        $scope.formData = {
            id: row.id,
            exam_name: row.exam_name,
            exam_date: row.exam_date,
            student_id: String(row.student_id || ''),
            subject_id: row.subject_id ? String(row.subject_id) : '',
            total_marks: row.total_marks,
            obtained_marks: row.obtained_marks,
            remark: row.remark || ''
        };
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    $scope.saveMark = function() {

        $scope.saving = true;
        DBService.postCall({}, '/api/teachers/exam-marks/store').then(function(data) {
            $scope.saving = false;
            if (data.success) {
                alert(data.message || 'Saved successfully.');
                $scope.rows = data.rows ;
                $scope.resetForm();
            } else {
                alert((data.message) ? data.message : 'Unable to save marks.');
            }
        });
    };
});

// *** workLogCtrl ***
app.controller('workLogCtrl', function($scope , DBService){
    $scope.worklog = [];
    $scope.users = [];
    $scope.processing = false;
    $scope.formData = {
        date : '',
        day_data : []
    };
    $scope.filter = {};

    $scope.init = function() {
        DBService.postCall($scope.filter, '/api/worklog/init').then(function(data) {
            if (data.success) {
                $scope.worklog = data.worklog ;
                $scope.users = data.users;
            }
        });
    };

    $scope.resetFilter = function(){
        $scope.filter = {};
        $scope.init();
    };

    $scope.openAddModal = function() {
        $scope.formData = { date: '', day_data : []};
        $('#worklogModal').modal('show');
        $scope.getDayData();
    };

    $scope.addMoreItem = function(){
        $scope.formData.day_data.push({remark:'', hours:'0'});
    }

    $scope.removeItem = function(index){
        // bootbox.confirm("Do you want to remove the item?",function(res){
            // if(res){
                // $scope.$apply(() => {
                    $scope.formData.day_data.splice(index,1);
                // });
            // }
        // });
       
    }


    $scope.getDayData = function() {
        DBService.postCall($scope.formData, '/api/worklog/edit').then(function(data) {
            if (data.success) {
                $scope.formData.day_data = data.day_data;
            }
        });
    };

    $scope.saveWorklog = function() {

        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/worklog/store').then(function(data) {
            $scope.processing = false;
            if (data.success) {
                $('#worklogModal').modal('hide');
                $scope.init();
                alert(data.message || 'Saved successfully.');
            } else {
                alert((data.message) ? data.message : 'Unable to save subject.');
            }
        });
    };

});

app.controller('addTeacherCtrl', function($scope , DBService){
    $scope.processing = false;
    $scope.formData = {
        enc_id: '',
        first_name: '',
        last_name: '',
        dob: '',
        gender: '',
        mobile: '',
        email: '',
        address: '',
        aadhar_no: '',
        document_type: 'Aadhar',
        document_no: '',
        active: '1',
        salary_components: [
            { component_name: 'Basic Salary', component_type: 'earning', amount: '' }
        ],
        bank_details: {
            account_holder_name: '',
            bank_name: '',
            account_number: '',
            ifsc_code: '',
            branch_name: '',
            upi_id: ''
        }
    };

    $scope.blood_groups = [];
    $scope.religions = [];
    $scope.casts = [];
    $scope.standards = [];

    $scope.init = function(teacher_token) {


        DBService.postCall({ teacher_token:teacher_token}, '/api/admin/teachers/init-details').then(function(data) {
            if (data.success) {
                if(data.teacher){
                    if (data.teacher.dob) {
                           data.teacher.dob = new Date(data.teacher.dob);
                        }
                    $scope.formData = data.teacher;                
                }

                $scope.blood_groups = data.blood_groups;
                $scope.religions = data.religions;
                $scope.casts = data.casts;
            }
            
        });
    };

    $scope.submit = function() {

        $scope.processing = true;

        DBService.erpPostCall($scope.formData, '/api/admin/teachers/store').then(function(data){

            $scope.processing = false;

            if (data.success) {
                alert(data.message);
                window.location.href = base_url + '/admin/teachers';
            } else {
                let firstError = Object.values(data.errors)[0][0];
                alert(firstError);
                $scope.errors = data.errors;
            }

        });
    };
    
    $scope.addSalaryComponent = function() {
        $scope.formData.salary_components.push({
            component_name: '',
            component_type: 'earning',
            amount: ''
        });
    };

    $scope.removeSalaryComponent = function(index) {
        if (($scope.formData.salary_components || []).length <= 1) {
            return;
        }
        $scope.formData.salary_components.splice(index, 1);
    };

    $scope.totalEarning = function() {
        var total = 0;
        angular.forEach($scope.formData.salary_components || [], function(item) {
            if ((item.component_type || 'earning') !== 'deduction') {
                total += parseFloat(item.amount || 0);
            }
        });
        return total;
    };

    $scope.totalDeduction = function() {
        var total = 0;
        angular.forEach($scope.formData.salary_components || [], function(item) {
            if ((item.component_type || 'earning') === 'deduction') {
                total += parseFloat(item.amount || 0);
            }
        });
        return total;
    };

    $scope.totalNet = function() {
        return Math.max(0, $scope.totalEarning() - $scope.totalDeduction());
    };
});

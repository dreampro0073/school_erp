
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


app.controller('superAdminUsersCtrl', function($scope , DBService, Upload){
    $scope.loading = true;
    $scope.processing = false;
    $scope.type = "";
    $scope.dataSet = [];
    $scope.formData = {};
    // $scope.today = '';
    // $scope.children = [];
    $scope.sch_id = 0;

    $scope.init = function() {
        $scope.dataSet = [];
        DBService.postCall({type : $scope.type}, '/api/super-admin/users/init').then(function(res) {
            if(res.success){
                data = res.data;
                $scope.dataSet = data.dataSet;
            }
        });
    };

    $scope.addSchool = function() {
        if ($scope.sch_id > 0) {
            DBService.postCall({ id: $scope.sch_id }, '/api/super-admin/users/edit').then(function(data) {
                if (data.success) {
                    $scope.formData = data.user;
                } else {
                    alert(data.message);
                }
            });
        }
    };

    $scope.submit = function(){
        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/super-admin/users/submit-users').then(function(data){
            alert(data.message);
            if (data.success) {
                window.location.href = base_url + data.url;
            }
            $scope.processing = false;
        })
    }

    $scope.uploadFile = function (file, name, object) {

      
        if (file.size > 1024 * 1024) {
            alert("File size must be less than 1MB");
            return;
        }

        object.uploading = true;

        var url = base_url + '/api/super-admin/users/uploadFile';

        Upload.upload({
            url: url,
            data: {
                media: file
            }
        }).then(function (resp) {

            if (resp.data.success) {

                object[name] = resp.data.data.media_link;
                object[name + '_link'] = resp.data.data.media_link;

            } else {
                alert(resp.data.message);
            }

            object.uploading = false;

        }, function (resp) {
            object.uploading = false;
            alert("Upload failed, please try again");

        }, function (evt) {
            object.progress = parseInt(100.0 * evt.loaded / evt.total);

        });


    };

    $scope.removeFile = function (object, name) {
        object[name] = '';
        object[name + '_link'] = '';
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

    $scope.deleteTeacher = function(item, index) {

        if (!confirm("Are you sure you want to delete this record?")) {
            return;
        }

        $scope.loading = true;

        DBService.postCall(
            { unique_id: item.unique_id },
            '/api/admin/teachers/delete'
        )
        .then(function(data) {

            if (data.success) {
                $scope.teachers.splice(index, 1);
                $scope.totalRecords--;
            } else {
                alert(data.message || "Delete failed!");
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
                if(data.message){
                    alert(data.message);
                }

                if(data.errors && Object.keys(data.errors).length > 0){
                    let firstError = Object.values(data.errors)[0][0];
                    alert(firstError);
                    $scope.errors = data.errors;    
                }
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


// *** aspirantDashboardCtrl ***
app.controller('aspirantDashboardCtrl', function($scope , DBService, Upload){
    $scope.loading = false;
    $scope.today = '';
    $scope.aspirant = {};
    $scope.subjects = [];
    $scope.topics = [];
    $scope.selectedSubject = null;
    $scope.isTopicSidebarOpen = false;
    $scope.isTopicEditMode = false;
    $scope.topicProcessing = false;
    $scope.topicForm = { id: null, subject_id: null, name: '', status: 0 };
    $scope.questions = [];
    $scope.selectedTopic = null;
    $scope.questionProcessing = false;
    $scope.questionForm = {
        id: null,
        subject_id: null,
        topic_id: null,
        question: '',
        remarks: '',
        reference: '',
        opt_a: '',
        opt_b: '',
        opt_c: '',
        opt_d: '',
        answer: '',
        negative_marks: 0.33,
        paragraph_id: '',
        image_file: '',
        total_marks: 1,
        image_file_link: ''
    };
    $scope.answerMode = '';
    $scope.answerText = '';
    $scope.questionUploading = false;

    $scope.init = function() {
        $scope.loading = true;
        DBService.postCall({}, '/api/aspirant/dashboard/init').then(function(data) {
            $scope.aspirant = data.aspirant;
            $scope.loading = false;
        });
    };

    $scope.initSubjects = function() {
        $scope.loading = true;
        DBService.postCall({}, '/api/aspirant/subjects/init').then(function(data) {
            $scope.subjects = data.subjects || [];
            $scope.loading = false;
        });
    };

    $scope.openTopics = function(subject) {
        if (!subject || !subject.id) {
            return;
        }
        $scope.selectedSubject = subject;
        $scope.topics = [];
        $scope.initTopics(subject.id);
    };

    $scope.initTopics = function(subjectId) {
        if (!subjectId) {
            return;
        }
        $scope.loading = true;
        DBService.postCall({ subject_id: subjectId }, '/api/aspirant/topics/init').then(function(data) {
            $scope.topics = data.topics || [];
            $scope.loading = false;
        }, function () {
            $scope.loading = false;
        });
    };

    $scope.initTopicsPage = function(subject) {
        $scope.selectedSubject = subject || null;
        if (subject && subject.id) {
            $scope.initTopics(subject.id);
        }
    };

    $scope.openTopicSidebar = function(topic) {
        $scope.isTopicEditMode = !!(topic && topic.id);
        if ($scope.isTopicEditMode) {
            $scope.topicForm = angular.copy(topic);
        } else {
            $scope.topicForm = { id: null, subject_id: ($scope.selectedSubject ? $scope.selectedSubject.id : null), name: '', status: 0 };
        }
        $scope.isTopicSidebarOpen = true;
    };

    $scope.closeTopicSidebar = function() {
        $scope.isTopicSidebarOpen = false;
        $scope.isTopicEditMode = false;
        $scope.topicForm = { id: null, subject_id: ($scope.selectedSubject ? $scope.selectedSubject.id : null), name: '', status: 0 };
    };

    $scope.saveTopic = function() {
        if (!$scope.topicForm.subject_id) {
            alert('Subject not selected');
            return;
        }
        $scope.topicProcessing = true;
        DBService.postCall($scope.topicForm, '/api/aspirant/topics/store').then(function(data) {
            $scope.topicProcessing = false;
            if (data.success) {
                alert(data.message || 'Saved Successfully');
                $scope.closeTopicSidebar();
                $scope.initTopics($scope.selectedSubject ? $scope.selectedSubject.id : null);
            } else {
                alert(data.message || 'Something went wrong');
            }
        }, function () {
            $scope.topicProcessing = false;
        });
    };

    $scope.initQuestionsPage = function(subject, topic) {
        $scope.selectedSubject = subject || null;
        $scope.selectedTopic = topic || null;
        if (subject && topic && subject.id && topic.id) {
            $scope.initQuestions(subject.id, topic.id);
        }
    };

    $scope.initQuestions = function(subjectId, topicId) {
        if (!subjectId || !topicId) {
            return;
        }
        $scope.loading = true;
        DBService.postCall({ subject_id: subjectId, topic_id: topicId }, '/api/aspirant/questions/init').then(function(data) {
            $scope.questions = data.questions || [];
            $scope.loading = false;
        }, function () {
            $scope.loading = false;
        });
    };

    $scope.openQuestionModal = function(question) {
        var subjectId = $scope.selectedSubject ? $scope.selectedSubject.id : null;
        var topicId = $scope.selectedTopic ? $scope.selectedTopic.id : null;
        if (!subjectId || !topicId) {
            alert('Subject/Topic not selected');
            return;
        }
        if (question && question.id) {
            $scope.questionForm = angular.copy(question);
            $scope.questionForm.subject_id = subjectId;
            $scope.questionForm.topic_id = topicId;
            $scope.questionForm.image_file_link = $scope.questionForm.image_file || '';
            if ($scope.questionForm.image_file_link && $scope.questionForm.image_file_link.indexOf('http') !== 0) {
                $scope.questionForm.image_file_link = base_url + '/' + $scope.questionForm.image_file_link;
            }
        } else {
            $scope.questionForm = {
                id: null,
                subject_id: subjectId,
                topic_id: topicId,
                question: '',
                remarks: '',
                reference: '',
                opt_a: '',
                opt_b: '',
                opt_c: '',
                opt_d: '',
                answer: '',
                negative_marks: 0.33,
                paragraph_id: '',
                image_file: '',
                total_marks: 1,
                image_file_link: ''
            };
        }
        $scope.syncAnswerMode();
        $('#questionModal').modal('show');
    };

    $scope.syncAnswerMode = function() {
        var ans = ($scope.questionForm.answer || '').toString().trim();
        if (['A', 'B', 'C', 'D'].indexOf(ans) !== -1) {
            $scope.answerMode = ans;
            $scope.answerText = '';
        } else {
            $scope.answerMode = 'TEXT';
            $scope.answerText = ans;
        }
    };

    $scope.onAnswerModeChange = function() {
        if ($scope.answerMode !== 'TEXT') {
            $scope.answerText = '';
        }
    };

    $scope.applyAnswer = function() {
        if ($scope.answerMode === 'TEXT') {
            $scope.questionForm.answer = ($scope.answerText || '').toString();
        } else {
            $scope.questionForm.answer = $scope.answerMode;
        }
    };

    $scope.uploadQuestionImage = function (file) {
        if (!file) {
            return;
        }
        if (file.size > 2 * 1024 * 1024) {
            alert("File size must be less than 2MB");
            return;
        }
        $scope.questionUploading = true;
        var url = base_url + '/api/aspirant/questions/upload-image';
        Upload.upload({
            url: url,
            data: {
                media: file
            }
        }).then(function (resp) {
            if (resp.data.success) {
                $scope.questionForm.image_file = resp.data.data.media_link;
                $scope.questionForm.image_file_link = resp.data.data.media_link;
            } else {
                alert(resp.data.message || 'Upload failed');
            }
            $scope.questionUploading = false;
        }, function () {
            $scope.questionUploading = false;
            alert("Upload failed, please try again");
        });
    };

    $scope.removeQuestionImage = function () {
        $scope.questionForm.image_file = '';
        $scope.questionForm.image_file_link = '';
    };

    $scope.saveQuestion = function() {
        if (!$scope.questionForm.subject_id || !$scope.questionForm.topic_id) {
            alert('Subject/Topic not selected');
            return;
        }
        $scope.applyAnswer();
        $scope.questionProcessing = true;
        DBService.postCall($scope.questionForm, '/api/aspirant/questions/store').then(function(data) {
            $scope.questionProcessing = false;
            if (data.success) {
                $('#questionModal').modal('hide');
                alert(data.message || 'Saved Successfully');
                $scope.initQuestions($scope.selectedSubject.id, $scope.selectedTopic.id);
            } else {
                alert(data.message || 'Something went wrong');
            }
        }, function () {
            $scope.questionProcessing = false;
        });
    };
});

// *** practiceCtrl ***
app.controller('practiceCtrl', function($scope , DBService, $interval){
    $scope.subjects = [];
    $scope.topics = [];
    $scope.selectedSubjectId = '';
    $scope.selectedTopicIds = {};
    $scope.questions = [];
    $scope.currentIndex = -1;
    $scope.currentQuestion = null;
    $scope.currentOptions = [];
    $scope.timeLeft = 30;
    $scope.timerHandle = null;
    $scope.loading = false;
    $scope.showAnswer = false;
    $scope.userAnswer = '';
    $scope.answerMode = 'A';
    $scope.answerText = '';
    $scope.referenceQuestion = null;

    $scope.init = function() {
        DBService.postCall({}, '/api/aspirant/practice/init').then(function(data) {
            if (data.success) {
                $scope.subjects = data.subjects || [];
            }
        });
    };

    $scope.onSubjectChange = function() {
        $scope.topics = [];
        $scope.selectedTopicIds = {};
        $scope.questions = [];
        $scope.currentIndex = -1;
        $scope.currentQuestion = null;
        $scope.stopTimer();
        if (!$scope.selectedSubjectId) {
            return;
        }
        DBService.postCall({ subject_id: $scope.selectedSubjectId }, '/api/aspirant/topics/init').then(function(data) {
            $scope.topics = data.topics || [];
        });
    };

    $scope.toggleTopic = function(topicId) {
        $scope.selectedTopicIds[topicId] = !$scope.selectedTopicIds[topicId];
    };

    $scope.getSelectedTopicIds = function() {
        return Object.keys($scope.selectedTopicIds).filter(function(id){
            return $scope.selectedTopicIds[id];
        }).map(function(id){ return parseInt(id, 10); });
    };

    $scope.startPractice = function() {
        if (!$scope.selectedSubjectId) {
            alert('Select subject');
            return;
        }
        if ($scope.getSelectedTopicIds().length === 0) {
            alert('Select at least one topic');
            return;
        }
        if ($scope.currentQuestion) {
            return;
        }
        $scope.fetchNextQuestion();
    };

    $scope.fetchNextQuestion = function() {
        var topicIds = $scope.getSelectedTopicIds();
        var excludeIds = $scope.questions.map(function(q){ return q.id; });
        $scope.loading = true;
        DBService.postCall({
            subject_id: $scope.selectedSubjectId,
            topic_ids: topicIds,
            exclude_ids: excludeIds
        }, '/api/aspirant/practice/random-question').then(function(data) {
            $scope.loading = false;
            if (!data.success) {
                alert(data.message || 'No question found');
                return;
            }
            var q = data.question;
            q._user_answer = '';
            q._show_answer = false;
            q._is_correct = false;
            $scope.questions.push(q);
            $scope.currentIndex = $scope.questions.length - 1;
            $scope.loadCurrentQuestion();
        }, function () {
            $scope.loading = false;
        });
    };

    $scope.loadCurrentQuestion = function() {
        $scope.currentQuestion = $scope.questions[$scope.currentIndex] || null;
        if (!$scope.currentQuestion) {
            $scope.currentOptions = [];
            return;
        }
        $scope.currentOptions = buildOptionList($scope.currentQuestion);
        $scope.userAnswer = $scope.currentQuestion._user_answer || '';
        $scope.showAnswer = $scope.currentQuestion._show_answer || false;
        $scope.resetAnswerMode();
        if ($scope.showAnswer) {
            $scope.stopTimer();
        } else {
            $scope.startTimer();
        }
    };

    $scope.resetAnswerMode = function() {
        var ans = ($scope.userAnswer || '').toString().trim();
        if (['A','B','C','D'].indexOf(ans) !== -1) {
            $scope.answerMode = ans;
            $scope.answerText = '';
        } else {
            $scope.answerMode = ans ? 'TEXT' : '';
            $scope.answerText = ans;
        }
    };

    $scope.onAnswerModeChange = function() {
        if ($scope.answerMode !== 'TEXT') {
            $scope.answerText = '';
        }
    };

    $scope.applyAnswer = function() {
        if ($scope.answerMode === 'TEXT') {
            $scope.userAnswer = ($scope.answerText || '').toString();
        } else {
            $scope.userAnswer = $scope.answerMode;
        }
    };

    $scope.startTimer = function() {
        $scope.stopTimer();
        $scope.timeLeft = 30;
        $scope.timerHandle = $interval(function(){
            $scope.timeLeft -= 1;
            if ($scope.timeLeft <= 0) {
                $scope.timeLeft = 0;
                $scope.revealAnswer();
            }
        }, 1000);
    };

    $scope.stopTimer = function() {
        if ($scope.timerHandle) {
            $interval.cancel($scope.timerHandle);
            $scope.timerHandle = null;
        }
    };

    $scope.selectOption = function(letter) {
        if ($scope.showAnswer) {
            return;
        }
        $scope.answerMode = letter;
        $scope.onAnswerModeChange();
        $scope.applyAnswer();
    };

    $scope.submitAnswer = function() {
        if ($scope.showAnswer) {
            return;
        }
        $scope.applyAnswer();
        $scope.revealAnswer();
    };

    $scope.revealAnswer = function() {
        if (!$scope.currentQuestion) {
            return;
        }
        $scope.stopTimer();
        var correct = ($scope.currentQuestion.answer || '').toString().trim();
        var user = ($scope.userAnswer || '').toString().trim();
        $scope.currentQuestion._user_answer = user;
        $scope.currentQuestion._show_answer = true;
        $scope.currentQuestion._is_correct = (user !== '' && user === correct);
        $scope.showAnswer = true;
    };

    $scope.nextQuestion = function() {
        if ($scope.currentIndex < $scope.questions.length - 1) {
            $scope.currentIndex += 1;
            $scope.loadCurrentQuestion();
            return;
        }
        $scope.fetchNextQuestion();
    };

    $scope.prevQuestion = function() {
        if ($scope.currentIndex > 0) {
            $scope.currentIndex -= 1;
            $scope.loadCurrentQuestion();
        }
    };

    function buildOptionList(question) {
        if (!question) {
            return [];
        }
        var options = [
            { key: 'A', text: question.opt_a },
            { key: 'B', text: question.opt_b },
            { key: 'C', text: question.opt_c },
            { key: 'D', text: question.opt_d }
        ];
        return options.filter(function(opt){ return opt.text && opt.text !== ''; });
    }

    $scope.optionClass = function(letter) {
        if (!$scope.showAnswer) {
            return ($scope.answerMode === letter ? 'border-primary-600 text-primary-600' : '');
        }
        var correct = ($scope.currentQuestion.answer || '').toString().trim();
        var user = ($scope.currentQuestion._user_answer || '').toString().trim();
        if (letter === correct) {
            return 'border-success-600 text-success-600';
        }
        if (letter === user && user !== correct) {
            return 'border-danger-600 text-danger-600';
        }
        return '';
    };

    $scope.showReference = function() {
        if (!$scope.currentQuestion) {
            return;
        }
        $scope.referenceQuestion = $scope.currentQuestion;
        $('#referenceModal').modal('show');
    };
});

app.controller('examCtrl', function($scope, $http, $interval, $timeout, $window) {
    var timerPromise = null;
    var savePromises = {};
    var localStorageKey = 'aspirant_exam_state';

    $scope.loading = false;
    $scope.processing = false;
    $scope.answerKeyLoading = false;
    $scope.subjects = [];
    $scope.selectedSubjects = {};
    $scope.questions = [];
    $scope.answerMap = {};
    $scope.visitedMap = {};
    $scope.currentQuestionIndex = 0;
    $scope.examId = '';
    $scope.examState = 'entry';
    $scope.timeLeft = 3600;
    $scope.result = null;
    $scope.answerKey = [];
    $scope.showAnswerKey = false;
    $scope.errorMessage = '';

    function apiConfig(method, route, payload, params) {
        return {
            method: method,
            url: base_url + route,
            data: payload || {},
            params: params || {},
            headers: {
                'apiToken': api_key
            }
        };
    }

    function setDraftState() {
        if (!$scope.examId) {
            return;
        }

        var payload = {
            exam_id: $scope.examId,
            answer_map: $scope.answerMap,
            visited_map: $scope.visitedMap,
            current_question_index: $scope.currentQuestionIndex,
            exam_state: $scope.examState
        };

        $window.localStorage.setItem(localStorageKey, JSON.stringify(payload));
    }

    function readDraftState() {
        try {
            return JSON.parse($window.localStorage.getItem(localStorageKey) || 'null');
        } catch (e) {
            return null;
        }
    }

    function clearDraftState() {
        $window.localStorage.removeItem(localStorageKey);
    }

    function beforeUnloadHandler(event) {
        if ($scope.examState !== 'running') {
            return;
        }

        event.preventDefault();
        event.returnValue = 'Your exam is in progress.';
        return event.returnValue;
    }

    function cancelSavePromises() {
        angular.forEach(savePromises, function(promise) {
            $timeout.cancel(promise);
        });
        savePromises = {};
    }

    function stopTimer() {
        if (timerPromise) {
            $interval.cancel(timerPromise);
            timerPromise = null;
        }
    }

    function startTimer() {
        stopTimer();
        timerPromise = $interval(function() {
            if ($scope.timeLeft > 0) {
                $scope.timeLeft -= 1;
            }

            if ($scope.timeLeft <= 0) {
                $scope.timeLeft = 0;
                stopTimer();
                $scope.submitExam(true);
            }
        }, 1000);
    }

    function hydrateExamState(response) {
        var exam = response.exam || {};
        var draft = readDraftState();
        var serverAnswerMap = response.answer_map || {};

        $scope.examId = exam.exam_id || '';
        $scope.questions = response.questions || [];
        $scope.answerMap = angular.extend({}, serverAnswerMap, draft && draft.exam_id === exam.exam_id ? (draft.answer_map || {}) : {});
        $scope.visitedMap = draft && draft.exam_id === exam.exam_id ? (draft.visited_map || {}) : {};
        $scope.currentQuestionIndex = draft && draft.exam_id === exam.exam_id ? parseInt(draft.current_question_index || 0, 10) : 0;
        $scope.timeLeft = parseInt(exam.remaining_seconds || 0, 10);
        $scope.examState = exam.status === 'submitted' ? 'result' : 'running';
        $scope.showAnswerKey = false;
        $scope.answerKey = [];
        $scope.errorMessage = '';

        if ($scope.currentQuestionIndex >= $scope.questions.length) {
            $scope.currentQuestionIndex = 0;
        }

        if ($scope.questions[$scope.currentQuestionIndex]) {
            $scope.visitedMap[$scope.questions[$scope.currentQuestionIndex].id] = true;
        }

        setDraftState();

        if ($scope.examState === 'running') {
            startTimer();
        } else {
            stopTimer();
            clearDraftState();
            $scope.loadResult();
        }
    }

    $scope.init = function() {
        $scope.loading = true;
        $window.addEventListener('beforeunload', beforeUnloadHandler);

        $http(apiConfig('GET', '/api/subjects')).then(function(response) {
            var data = response.data || {};
            $scope.subjects = data.subjects || [];
            $scope.loading = false;

            var draft = readDraftState();
            if (draft && draft.exam_id) {
                $scope.restoreDraft();
            }
        }, function() {
            $scope.loading = false;
            $scope.errorMessage = 'Unable to load subjects.';
        });
    };

    $scope.hasDraftExam = function() {
        var draft = readDraftState();
        return !!(draft && draft.exam_id);
    };

    $scope.restoreDraft = function() {
        var draft = readDraftState();
        if (!draft || !draft.exam_id) {
            return;
        }

        $scope.loading = true;
        $http(apiConfig('GET', '/api/get-questions', null, { exam_id: draft.exam_id })).then(function(response) {
            $scope.loading = false;
            if (response.data && response.data.success) {
                hydrateExamState(response.data);
            } else {
                clearDraftState();
                $scope.errorMessage = (response.data && response.data.message) ? response.data.message : 'Unable to resume exam.';
            }
        }, function() {
            $scope.loading = false;
            $scope.errorMessage = 'Unable to resume exam.';
        });
    };

    $scope.toggleSubject = function(subjectId) {
        $scope.selectedSubjects[subjectId] = !$scope.selectedSubjects[subjectId];
        $scope.errorMessage = '';
    };

    $scope.getSelectedSubjectCount = function() {
        return Object.keys($scope.selectedSubjects).filter(function(id) {
            return $scope.selectedSubjects[id];
        }).length;
    };

    $scope.getSelectedSubjectIds = function() {
        return Object.keys($scope.selectedSubjects).filter(function(id) {
            return $scope.selectedSubjects[id];
        }).map(function(id) {
            return parseInt(id, 10);
        });
    };

    $scope.startExam = function() {
        if ($scope.getSelectedSubjectCount() < 3) {
            $scope.errorMessage = 'Please select at least 3 subjects.';
            return;
        }

        $scope.processing = true;
        $scope.errorMessage = '';

        $http(apiConfig('POST', '/api/start-exam', {
            subject_ids: $scope.getSelectedSubjectIds()
        })).then(function(response) {
            var data = response.data || {};
            if (!data.success) {
                $scope.processing = false;
                $scope.errorMessage = data.message || 'Unable to start exam.';
                return;
            }

            $scope.examId = data.exam_id;
            $scope.questions = [];
            $scope.answerMap = {};
            $scope.visitedMap = {};
            $scope.currentQuestionIndex = 0;
            $scope.result = null;
            $scope.answerKey = [];
            $scope.showAnswerKey = false;
            setDraftState();
            $scope.loadQuestions();
        }, function() {
            $scope.processing = false;
            $scope.errorMessage = 'Unable to start exam.';
        });
    };

    $scope.loadQuestions = function() {
        if (!$scope.examId) {
            $scope.processing = false;
            return;
        }

        $scope.loading = true;
        $http(apiConfig('GET', '/api/get-questions', null, { exam_id: $scope.examId })).then(function(response) {
            $scope.loading = false;
            $scope.processing = false;
            if (response.data && response.data.success) {
                hydrateExamState(response.data);
            } else {
                $scope.errorMessage = (response.data && response.data.message) ? response.data.message : 'Unable to load questions.';
            }
        }, function() {
            $scope.loading = false;
            $scope.processing = false;
            $scope.errorMessage = 'Unable to load questions.';
        });
    };

    $scope.getCurrentQuestion = function() {
        return $scope.questions[$scope.currentQuestionIndex] || null;
    };

    $scope.getQuestionOptions = function(question) {
        if (!question) {
            return [];
        }

        return [
            { key: 'A', text: question.option1 },
            { key: 'B', text: question.option2 },
            { key: 'C', text: question.option3 },
            { key: 'D', text: question.option4 }
        ].filter(function(option) {
            return option.text !== null && option.text !== undefined && option.text !== '';
        });
    };

    $scope.goToQuestion = function(index) {
        if (index < 0 || index >= $scope.questions.length) {
            return;
        }

        $scope.currentQuestionIndex = index;
        if ($scope.questions[index]) {
            $scope.visitedMap[$scope.questions[index].id] = true;
        }
        setDraftState();
    };

    $scope.nextQuestion = function() {
        if ($scope.currentQuestionIndex < $scope.questions.length - 1) {
            $scope.goToQuestion($scope.currentQuestionIndex + 1);
        }
    };

    $scope.previousQuestion = function() {
        if ($scope.currentQuestionIndex > 0) {
            $scope.goToQuestion($scope.currentQuestionIndex - 1);
        }
    };

    $scope.selectAnswer = function(question, selectedOption) {
        if (!question || !$scope.examId) {
            return;
        }

        $scope.answerMap[question.id] = selectedOption;
        $scope.visitedMap[question.id] = true;
        setDraftState();

        if (savePromises[question.id]) {
            $timeout.cancel(savePromises[question.id]);
        }

        savePromises[question.id] = $timeout(function() {
            $http(apiConfig('POST', '/api/save-answer', {
                exam_id: $scope.examId,
                question_id: question.id,
                selected_option: selectedOption
            })).then(function(response) {
                var data = response.data || {};
                if (data.auto_submitted) {
                    $scope.result = data.result || null;
                    $scope.examState = 'result';
                    stopTimer();
                    clearDraftState();
                }
            });
        }, 500);
    };

    $scope.getPaletteClass = function(question, index) {
        var classes = [];
        if ($scope.answerMap[question.id]) {
            classes.push('answered');
        } else if ($scope.visitedMap[question.id]) {
            classes.push('visited');
        }
        if (index === $scope.currentQuestionIndex) {
            classes.push('current');
        }
        return classes.join(' ');
    };

    $scope.getAnsweredCount = function() {
        return Object.keys($scope.answerMap).filter(function(questionId) {
            return !!$scope.answerMap[questionId];
        }).length;
    };

    $scope.formatTime = function(totalSeconds) {
        var seconds = parseInt(totalSeconds || 0, 10);
        var hrs = Math.floor(seconds / 3600);
        var mins = Math.floor((seconds % 3600) / 60);
        var secs = seconds % 60;

        function pad(value) {
            return value < 10 ? '0' + value : '' + value;
        }

        return pad(hrs) + ':' + pad(mins) + ':' + pad(secs);
    };

    $scope.submitExam = function(isAutoSubmit) {
        if (!$scope.examId) {
            return;
        }

        cancelSavePromises();
        $scope.processing = true;

        $http(apiConfig('POST', '/api/submit-exam', {
            exam_id: $scope.examId,
            answers: $scope.answerMap
        })).then(function(response) {
            var data = response.data || {};
            $scope.processing = false;
            stopTimer();

            if (!data.success) {
                $scope.errorMessage = data.message || 'Unable to submit exam.';
                return;
            }

            $scope.result = data.result || null;
            $scope.examState = 'result';
            $scope.showAnswerKey = false;
            if (isAutoSubmit) {
                $scope.errorMessage = 'Time is over. Exam submitted automatically.';
            } else {
                $scope.errorMessage = '';
            }
            clearDraftState();
        }, function() {
            $scope.processing = false;
            $scope.errorMessage = 'Unable to submit exam.';
        });
    };

    $scope.loadResult = function() {
        if (!$scope.examId) {
            return;
        }

        $http(apiConfig('GET', '/api/result', null, { exam_id: $scope.examId })).then(function(response) {
            if (response.data && response.data.success) {
                $scope.result = response.data.result || null;
                $scope.examState = 'result';
            }
        });
    };

    $scope.loadAnswerKey = function() {
        if (!$scope.examId) {
            return;
        }

        $scope.answerKeyLoading = true;
        $http(apiConfig('GET', '/api/answer-key', null, { exam_id: $scope.examId })).then(function(response) {
            $scope.answerKeyLoading = false;
            if (response.data && response.data.success) {
                $scope.answerKey = response.data.answer_key || [];
                $scope.showAnswerKey = true;
            }
        }, function() {
            $scope.answerKeyLoading = false;
        });
    };

    $scope.resetToEntry = function() {
        stopTimer();
        cancelSavePromises();
        clearDraftState();
        $scope.selectedSubjects = {};
        $scope.questions = [];
        $scope.answerMap = {};
        $scope.visitedMap = {};
        $scope.currentQuestionIndex = 0;
        $scope.examId = '';
        $scope.timeLeft = 3600;
        $scope.result = null;
        $scope.answerKey = [];
        $scope.showAnswerKey = false;
        $scope.examState = 'entry';
        $scope.errorMessage = '';
    };

    $scope.$on('$destroy', function() {
        stopTimer();
        cancelSavePromises();
        $window.removeEventListener('beforeunload', beforeUnloadHandler);
    });
});

app.controller('studentCtrl', function($scope , $http, $timeout , DBService, Upload) {

    $scope.loading = false;

    $scope.students = [];

    $scope.currentPage = 1;
    $scope.totalPages = 1;
    $scope.totalRecords = 0;

    $scope.filter = {
        page:1,
        search:"",
        limit:10,
    };
    let searchTimeout = null;

    
    $scope.changePage = function(page){
        $scope.init(page);
    };
    $scope.onSearch = function(){

        if(searchTimeout){
            $timeout.cancel(searchTimeout);
        }

        searchTimeout = $timeout(function(){

            $scope.init(1);

        },400); // 400ms delay

    }
    $scope.init = function(page = 1){
        $scope.filter.page = page;
        $scope.loading = true;

        DBService.postCall($scope.filter,'/api/admin/students/init')
        .then(function(res){
            if(res.success){
                $scope.students = res.data.data;

                console.log($scope.students);

                $scope.currentPage = res.data.current_page;
                $scope.totalPages = res.data.last_page;
                $scope.totalRecords = res.data.total;

            }
            $scope.loading = false;
        });
    }   
});
app.controller('addStudentCtrl', function($scope , DBService,Upload){
    $scope.processing = false;
    $scope.formData = {
        enc_id: '',
        admission_no: '',
        first_name: '',
        last_name: '',
        dob: '',
        gender: '',
        mobile: '',
        email: '',
        address: '',
        aadhar_no: '',
        parent_name: '',
        parent_mobile: '',
        parent_email: '',
        parent_address: '',
        parent_aadhar_no: '',
        document_type: 'Aadhar',
        document_no: '',
        active: '1',
        student_photo:'',
        aadhar_card:'',
    };

    $scope.blood_groups = [];
    $scope.religions = [];
    $scope.casts = [];
    $scope.standards = [];

    $scope.init = function(student_token) {
        DBService.postCall({ student_token:student_token }, '/api/admin/students/init-details').then(function(data) {
            if (data.success) {
                if(data.student){
                    $scope.formData = data.student;                
                }

                $scope.blood_groups = data.blood_groups;
                $scope.religions = data.religions;
                $scope.casts = data.casts;
                $scope.standards = data.standards;
            }

        });
    };

    $scope.submit = function() {
        $scope.processing = true;

        // DBService.postCall($scope.formData, '/api/admin/students/store').then(function(data) {
        //     alert((data.message) ? data.message : 'Unable to save student.');
        //     if (data.success) {
        //         window.location.href = base_url + '/admin/students';
        //     }
        //     $scope.processing = false;
        // }, function() {
        //     $scope.processing = false;
        // });

        // DBService.postCall($scope.formData,'/api/admin/students/store').then(function(data){
          
        //     $scope.processing = false;
            
        //     if (data.success) {
        //         alert(data.message);
        //         window.location.href = base_url + '/admin/students';
        //     }else{
        //         alert(data.message);
        //     }
        // });

        DBService.erpPostCall($scope.formData, '/api/admin/students/store').then(function(data){

            $scope.processing = false;

            if (data.success) {
                alert(data.message);
                window.location.href = base_url + '/admin/students';
            } else {
                let firstError = Object.values(data.errors)[0][0];
                alert(firstError);
                $scope.errors = data.errors;
            }

        });
    };

    $scope.uploadFile = function (file, name, object) {

      
        if (file.size > 1024 * 1024) {
            alert("File size must be less than 1MB");
            return;
        }

        object.uploading = true;

        var url = base_url + '/api/admin/students/uploadFile';

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

app.controller('studentDetailsCtrl', function($scope , DBService){
    $scope.loading = true;
    $scope.details_loading = false;
    $scope.at_loading = false;
    $scope.exam_loading = false;
    $scope.leave_loading = false;
    $scope.fee_loading = false;
    
    $scope.student = null;
    $scope.standard_id = 0;
    $scope.tab = 1;
    $scope.student_token = "";
    $scope.changeTab = (tab) => {
        $scope.tab = tab;
    }

    $scope.years = [];

    $scope.formData = {
        fee_type_id:'',
        frequency_id:'',
        route_id:'',
        month:'',
        payment_mode:'',
    };
    $scope.isSidebarOpen = false;
    $scope.isEditMode = false;

    $scope.fee_types = [];
    $scope.fee_frequencies = [];
    $scope.fee_subs = [];
    $scope.payment_modes = [];
    $scope.routes = [];
    $scope.fee_amount = 0;

    $scope.currentPage = 1;
    $scope.totalPages = 1;
    $scope.totalRecords = 0;

    $scope.filter = {
        page:1,
        search:"",
        limit:10,
    };

    $scope.payments = [];

    $scope.getDetails = function() {
       
        $scope.details_loading = true;

        DBService.postCall({ student_token:$scope.student_token }, '/api/admin/students/get-profile-details').then(function(data) {
            if (data.success) {
                $scope.loading = false;
                $scope.details_loading = false;
                if(data.student){
                    $scope.student = data.student;                
                    $scope.standard_id = data.student.standard_id;                
                }
            }
        });
    };

    $scope.getAttendance = function() {
        $scope.attendance_data = [];
        $scope.at_loading = true;
        DBService.postCall({ student_token:$scope.student_token }, '/api/admin/students/get-attendance').then(function(data) {
            if (data.success) {
                $scope.attendance_data = data.attendance_data;
                $scope.at_loading = false;
            }
        });
    };
    $scope.getLeaves = function() {
        $scope.leaves = [];
        $scope.leave_loading = true;
        DBService.postCall({ student_token:$scope.student_token }, '/api/admin/students/get-leaves').then(function(data) {
            if (data.success) {
                $scope.leaves = data.leaves;
                $scope.leave_loading = false;
            }
        });
    };
    $scope.getExams = function() {
        $scope.exams = [];
        $scope.exam_loading = true;
        DBService.postCall({ student_token:$scope.student_token }, '/api/admin/students/get-exams').then(function(data) {
            if (data.success) {
                $scope.exams = data.exams;
                $scope.exam_loading = false;
            }
        });
    };

    $scope.getFees = function(page = 1){
        $scope.filter.page = page;
        $scope.payments = [];
        $scope.filter.page = page;
        $scope.filter.student_token = $scope.student_token;
       
        DBService.postCall($scope.filter,'/api/admin/students/get-fees')
        .then(function(res){
            if(res.success){
                $scope.payments = res.data.data;
                $scope.currentPage = res.data.current_page;
                $scope.totalPages = res.data.last_page;
                $scope.totalRecords = res.data.total;
            }
            $scope.loading = false;
        });
    }  

   
    $scope.changePage = function(page){
        if($scope.tab == 4){
            $scope.getFees(page);
        }
    };
    $scope.getFeeParams = function() {
        DBService.postCall({ student_token:$scope.student_token,standard_id:$scope.standard_id }, '/api/admin/students/get-fee-params').then(function(data) {
            if (data.success) {
                $scope.fee_types = data.fee_types;
                $scope.fee_frequencies = data.fee_frequencies;
                $scope.routes = data.routes;
                $scope.payment_modes = data.payment_modes;
                $scope.years = data.years;
                $scope.months = data.months;
            }
        });
    };
    $scope.fee_sub_loading = false;

    $scope.onChangeFeeType = () => {
        $scope.formData.amount = 0;
        $scope.formData.frequency_id = '';

        $scope.getFeeSubs();
    }

    $scope.getFeeSubs = function() {
        $scope.fee_subs = [];
        $scope.fee_sub_loading = true;
        $scope.formData.amount = 0;

        var send_data = {
            student_token:$scope.student_token,
            standard_id:$scope.standard_id,
            fee_type_id:$scope.formData.fee_type_id,
        }
        if($scope.formData.frequency_id != 0 && ($scope.formData.fee_type_id == 2 || $scope.formData.fee_type_id == 3)){
            send_data.frequency_id = $scope.formData.frequency_id;
        }
        if($scope.formData.fee_type_id == 3){
            send_data.route_id = $scope.formData.route_id;
        }
        DBService.postCall(send_data,'/api/admin/students/get-fee-subs').then(function(data) {
            if (data.success) {
                $scope.formData.amount = data.amount;
            }
        });
    };

    $scope.collect_fee_loading = false;
    $scope.collectFees = () => {
        $scope.collect_fee_loading = true;
        $scope.formData.student_token = $scope.student_token;
        $scope.formData.standard_id = $scope.standard_id;
        DBService.erpPostCall($scope.formData,'/api/admin/students/collect-fee').then(function(data) {
            if (data.success) {
                alert(data.message);
                $scope.closeSidebar();
                $scope.getFees();

                setTimeout(function(){
                    window.open(base_url+'/admin/students/receipt/'+data.payment_id,'_blank');
                }, 800);
            } else {
                if(data.length > 0){
                    let firstError = Object.values(data.errors)[0][0];
                    alert(firstError);
                    $scope.errors = data.errors;
                }else{
                    alert(data.message);
                }
            }
            $scope.collect_fee_loading = false;
        });
    }

    $scope.openFeeModal = function () {
        $scope.isEditMode = false;
        $scope.formData = {
            fee_type_id:'',
            frequency_id:'',
            month:'',
            payment_mode:'',
        };
        $scope.isSidebarOpen = true;
    };

    $scope.openEditFeeModal = function(item) {
        $scope.isSidebarOpen = false;
        DBService.postCall({id : item.id}, '/api/admin/school/class-edit').then(function(data) {
            if(data.success){
                $scope.isEditMode = true;
                $scope.formData = data.formData;
                $scope.isSidebarOpen = true;
                
            } else {
                alert(data.message);
            }
        });
    };

    $scope.closeSidebar = function () {
        $scope.isSidebarOpen = false;
        $scope.formData = {
            fee_type_id:'',
            frequency_id:'',
            month:'',
        };
    };
});

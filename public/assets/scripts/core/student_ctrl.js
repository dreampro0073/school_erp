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
app.controller('addStudentCtrl', function($scope , DBService){
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
        active: '1'
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
});

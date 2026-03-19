app.controller('schoolManagementCtrl', function($scope , DBService){
    $scope.loading = false;
    $scope.list_loading = false;
    $scope.type = "classes";
    $scope.standards = [];
    $scope.dataSet = [];
    $scope.sections = [];
    $scope.sessions = [];
    $scope.teachers = [];
    $scope.students = [];
    $scope.dataSet = [];
    $scope.days = [];
    $scope.formData = {};

    $scope.init = function(){
        $scope.loading = false;
        DBService.postCall({type : $scope.type}, '/api/admin/school/init').then(function(data) {
            if(data.success){
                $scope.standards = data.standards;
                $scope.sections = data.sections;
                $scope.sessions = data.sessions;
                $scope.teachers = data.teachers;
                $scope.students = data.students;
                $scope.days = data.days;
                $scope.loading = true;
            }
        });
    }

    $scope.typeSchedule = function() {
        $scope.list_loading = false;
        DBService.postCall({type : 'schedule'}, '/api/admin/school/schedule').then(function(data) {
            if(data.success){
                $
                $scope.list_loading = true;
            }
        });
    }; 

    $scope.submitSchedule = function () {
        $http.post('/api/admin/school/class-store', $scope.formData).then(function (res) {
            alert(res.data.message);
            $scope.formData = {};
        });
    };   

    $scope.typeClasses = function() {
        $scope.list_loading = false;
        DBService.postCall({type : 'classes'}, '/api/admin/school/classes').then(function(data) {
            if(data.success){
                $scope.sections
                $scope.list_loading = true;
            }
        });
    };  

    $scope.editData = function (item) {
        $scope.formData = angular.copy(item);
    };


    $scope.submitClass = function () {
        $http.post('/api/admin/school/class-store', $scope.formData).then(function (res) {
            alert(res.data.message);
            $scope.formData = {};
        });
    };
  

    $scope.typeExams = function() {
        $scope.list_loading = false;
        DBService.postCall({type : 'exams'}, '/api/admin/school/exams').then(function(data) {
            if(data.success){
                $scope.sections
                $scope.list_loading = true;
            }
        });
    };

    $scope.submitExam = function () {
        $http.post('/api/admin/school/class-store', $scope.formData).then(function (res) {
            alert(res.data.message);
            $scope.formData = {};
        });
    };    

    $scope.typeResults = function() {
        $scope.list_loading = false;
        DBService.postCall({type : 'results'}, '/api/admin/school/results').then(function(data) {
            if(data.success){
                $scope.sections
                $scope.list_loading = true;
            }
        });
    };

    $scope.submitResult = function () {
        $http.post('/api/admin/school/class-store', $scope.formData).then(function (res) {
            alert(res.data.message);
            $scope.formData = {};
        });
    };

    if($scope.type == "schedule"){
        $scope.typeSchedule();
    }    
    if($scope.type == "classes"){
        $scope.typeClasses();
    }    
    if($scope.type == "exams"){
        $scope.typeExams();
    }    
    if($scope.type == "results"){
        $scope.typeResults();
    }

});

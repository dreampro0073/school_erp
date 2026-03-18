app.controller('schoolManagementCtrl', function($scope , DBService){
    $scope.loading = true;
    $scope.type = "classes";
    $scope.standards = [];
    $scope.dataSet = [];
    $scope.sections = [];
    $scope.sessions = [];

    $scope.init = function(){
        DBService.postCall({type : $scope.type}, '/api/admin/school/init').then(function(data) {
            if(data.success){

                DBService.postCall({type : $scope.type}, '/api/admin/school/init').then(function(data) {
                    if(data.success){
                        $scope.sections
                        $scope.standards = data.standards;
                        $scope.sections = data.sections;
                        $scope.sessions = data.sessions;
                    }
                });
            }
        });
    };

    $scope.typeSchedule = function() {
        DBService.postCall({type : 'schedule'}, '/api/admin/school/schedule').then(function(data) {
            if(data.success){
                $scope.sections
            }
        });
    };    

    $scope.typeClasses = function() {
        DBService.postCall({type : 'classes'}, '/api/admin/school/classes').then(function(data) {
            if(data.success){
                $scope.sections
            }
        });
    };    

    $scope.typeExams = function() {
        DBService.postCall({type : 'exams'}, '/api/admin/school/exams').then(function(data) {
            if(data.success){
                $scope.sections
            }
        });
    };    

    $scope.typeResults = function() {
        DBService.postCall({type : 'results'}, '/api/admin/school/results').then(function(data) {
            if(data.success){
                $scope.sections
            }
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

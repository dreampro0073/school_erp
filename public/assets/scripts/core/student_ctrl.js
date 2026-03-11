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

        DBService.postCall($scope.filter,'/api/students/init')
        .then(function(res){
            if(res.success){
                $scope.students = res.data.data;

                $scope.currentPage = res.data.current_page;
                $scope.totalPages = res.data.last_page;
                $scope.totalRecords = res.data.total;

            }

            $scope.loading = false;

        });

    }

   
});


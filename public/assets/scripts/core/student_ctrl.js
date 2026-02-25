app.controller('studentCtrl', function($scope , $http, $timeout , DBService, Upload) {
    $scope.loading = false;
    $scope.students = [];
    $scope.formData = {};

    $scope.init = function(){

        $scope.loading = true;
        DBService.postCall($scope.filter,'/api/students/init').then(function(data){
            if(data.success){
                $scope.students = data.students;
            }
            $scope.loading = false;
        }); 
    }
    $scope.onSearch = function(){
        $scope.init();
    }
    $scope.filterClear = function(){
        $scope.filter = { };
        $scope.init();
    }

    $scope.edit = function(g_stock_id){
        DBService.postCall({g_stock_id:g_stock_id},'/api/godowns/edit').then(function(data){
            if (data.success) {
                if (data.g_entry) {
                    $scope.formData = data.g_entry;
                }
            }
        });
    }

    

    $scope.onSubmit = function(){
        $scope.processing = true;
        console.log($scope.formData);
        DBService.postCall($scope.formData,'/api/godowns/store').then(function(data){
            if (data.success) {
                $scope.init();  
                $scope.setNull();
            }
            alert(data.message);
            $scope.processing = false;
        });
    }
    $scope.setNull = () => {
        $scope.formData = {
            stock:'',
            date:'',
            barcodevalue:'',
            id:'',
        }; 
        $scope.g_stock_id = 0; 

    }

});


app.controller('godownsCtrl', function($scope , $http, $timeout , DBService, Upload) {
    $scope.g_stock_id = 0;
    $scope.loading = false;

    $scope.canteen_items = [];
    
    $scope.formData = {
        stock:'',
        date:'',
        barcodevalue:'',
        id:'',
    }  

    $scope.selectConfig = {
        valueField: 'barcodevalue',
        labelField: 'item_name',
        maxItems:1,
        searchField: 'item_name',
        create: false,
        onInitialize: function(selectize){
              
        }
    }

    $scope.init = function(){

        $scope.loading = true;
        DBService.postCall($scope.filter,'/api/godowns/init').then(function(data){
            if(data.success){
                $scope.g_entries = data.g_entries;
                $scope.canteen_items = data.canteen_items;
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
app.controller('godownsHistoryCtrl', function($scope , $http, $timeout , DBService, Upload) {
    $scope.g_stock_id = 0;
    $scope.loading = false;
    $scope.stock_history = [];

    $scope.init = function(){

        $scope.loading = true;
        DBService.postCall($scope.filter,'/api/godowns/init-history/'+$scope.g_stock_id).then(function(data){
            if(data.success){
                $scope.stock_history = data.stock_history;
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


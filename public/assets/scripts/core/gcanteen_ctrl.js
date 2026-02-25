app.controller('gCanteenItemsCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading = false;
    $scope.formData = {
        item_name:'',
        item_short_name:'',
        price:'',
        barcodevalue:'',
    };
    $scope.stockData = {
        stock:'',
    };
    $scope.filter = {
        barcodevalue_search:'',
    };
    $scope.canteen_item_id = 0;
    $scope.canteen_item_stock_id = 0;
    $scope.canteen_items = [];
 
    $scope.init = function () {
        DBService.postCall($scope.filter, '/api/godown-canteen/canteen-items/init').then((data) => {
            $scope.canteen_items = data.canteen_items;
            $("#barcodevalue").focus();
        });
    }
    $scope.filterClear = function(){
        $scope.filter = {
            barcodevalue_search:'',
        };
        $scope.init();
    }

    $scope.edit = function(canteen_item_id){
        $scope.canteen_item_id = canteen_item_id;
        DBService.postCall({canteen_item_id : $scope.canteen_item_id}, '/api/godown-canteen/canteen-items/edit').then((data) => {
            if (data.success) {
                $scope.formData = data.canteen_item;
                $("#exampleModalCenter").modal("show");
            }
        });
    }

    $scope.hideModal = () => {
        $("#exampleModalCenter").modal("hide");
        $scope.canteen_item_id = 0;
        $scope.formData = {
            item_name:'',
            item_short_name:'',
            price:'',
            barcodevalue:'',
        };
        $scope.init();
    }

    $scope.add = () => {
        $scope.canteen_item_id = 0;
        $scope.formData = {
            item_name:'',
            item_short_name:'',
            price:'',
        };
        $("#barcodevalue").focus();
        setTimeout(function() {
            $("#exampleModalCenter").modal("show");
        }, 300); 

        
        
    }

    $scope.onSubmit = function () {
        console.log($scope.formData);
        $scope.loading = true;
        DBService.postCall($scope.formData, '/api/godown-canteen/canteen-items/store').then((data) => {
            if (data.success) {
                alert(data.message);
                $("#exampleModalCenter").modal("hide");

                $scope.formData = {
                    item_name:'',
                    item_short_name:'',
                    price:'',
                    barcodevalue:'',
                };
                $scope.init();
            }else{
                alert(data.message);
            }
            $scope.loading = false;
        });
    }

    $scope.initStocks = function () {
        $scope.filter.canteen_item_id = $scope.canteen_item_id;
        console.log($scope.filter+'hello');
        DBService.postCall($scope.filter, '/api/godown-canteen/canteen-items/stocks/init').then((data) => {
            $scope.item_stocks = data.item_stocks;
        });
    }

    $scope.hideStockModal = () => {
        $("#stockModal").modal("hide");
        $scope.stockData = {
            stock:'',
        };
        $scope.initStocks();
    }

    $scope.addStock = () => {
        $("#stockModal").modal("show");
        $scope.stockData = {
            stock:'',
        };
    }

    $scope.editStock = function(canteen_item_stock_id){
        $scope.canteen_item_stock_id = canteen_item_stock_id;
        DBService.postCall({canteen_item_stock_id : $scope.canteen_item_stock_id}, '/api/godown-canteen/canteen-items/stocks/edit').then((data) => {
            if (data.success) {
                $scope.stockData = data.item_stock;
                $("#stockModal").modal("show");
            }
        });
    }

    $scope.onStockSubmit = function () {
        $scope.loading = true;
        $scope.stockData.canteen_item_id = $scope.canteen_item_id;
        DBService.postCall($scope.stockData, '/api/godown-canteen/canteen-items/stocks/store').then((data) => {
            if (data.success) {
                alert(data.message);
                $("#stockModal").modal("hide");

                $scope.stockData = {
                    stock:'',
                };
                $scope.initStocks();
            }else{
                alert(data.message);
            }
            $scope.loading = false;
        });
    }
});

app.controller('gDailyEntryCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading = false;
    $scope.formData = {
        name:'',
        mobile:'',
        pay_type: '',
        total_amount: 0,
        total_item:0,
        products: [{demo:''}],

    };

    $scope.products = [];
    $scope.product = {
        canteen_item_id:0,
        item_name:'',
        quantity:1,
    }; 


    // $scope.total_amount= 0;
    $scope.filter = {};
    $scope.entry_id = 0;
    $scope.daily_entries = [];
    $scope.canteen_items = [];

    $scope.selectConfig = {
        valueField: 'barcodevalue',
        labelField: 'item_name',
        maxItems:1,
        searchField: 'item_name',
        create: false,
        onInitialize: function(selectize){
              
        }
    }
    $scope.setNull = () => {
        $scope.entry_id = 0;
        $scope.formData = {
            name:'',
            mobile:'',
            pay_type:'',
            total_amount: 0,
            total_item:0,
            products: [{demo:''}],

        };
        $scope.products = [];
        $scope.product = {
            canteen_item_id:0,
            item_name:'',
            quantity:1,
        }; 
        $scope.productname = '';

    }
    $scope.init = function () {
        DBService.postCall($scope.filter, '/api/godown-canteen/daily-entries/init').then((data) => {
            $scope.daily_entries = data.daily_entries;
            $scope.canteen_items = data.canteen_items;
            $("#productname").focus();
            $scope.setNull();
        });
    }

    $scope.filterClear = function(){
        $scope.filter = {};
        $scope.init();
    }

    $scope.edit = function(entry_id){
        $scope.setNull();
        $scope.entry_id = entry_id;
        DBService.postCall({entry_id : $scope.entry_id}, '/api/godown-canteen/daily-entries/edit-init').then((data) => {
            if (data.success) {
                $scope.formData = data.s_entry;
                $("#exampleModalCenter").modal("show");
            }
        });
    }

    $scope.hideModal = () => {
        $("#exampleModalCenter").modal("hide");
        $scope.entry_id = 0;
        $scope.init();
    }

    $scope.add = () => {
        $("#productname").focus();
        $scope.setNull();
        setTimeout(function() {
            $("#exampleModalCenter").modal("show");
        }, 300); 
       
    }

    $scope.onSubmit = function () {
        $scope.formData.products = $scope.products;
        if($scope.products.length == 0){
            alert("Plese select at least one item.");
            
            return;
        }
       
        if($scope.formData.pay_type == ''){
            alert("Plese select the pay type");
           
            return;
        }
        if($scope.formData.total_amount == 0){
            alert("Please select at least one item");
           
            return;
        }

        $scope.loading = true;

        DBService.postCall($scope.formData, '/api/godown-canteen/daily-entries/store').then((data) => {
            if (data.success) {
                alert(data.message);
                $("#exampleModalCenter").modal("hide");
                $scope.products = [];
                $scope.init();
                setTimeout(function(){
                    window.open(base_url+'/admin/daily-entries/print/'+data.entry_id,'_blank');
                }, 800);
            }else{
                alert(data.message);
            }
            $scope.loading = false;
        });
    }

    $scope.onAddProdcut = () => {
        
        let products = $scope.products;
        var my_item = $scope.canteen_items.find(item => item.barcodevalue == $scope.productname);

        let index = -1;
        if(products.length > 0){
            index = products.findIndex(item => item.barcodevalue == $scope.productname);
        }

        // if (index == -1) {
        //     my_item.paid_amount = my_item.price*1;
        //     my_item.quantity = 1;
        //     products.push(my_item);
        // } else {
        //     $scope.products[index].quantity += 1;
        //     $scope.products[index].paid_amount = my_item.price*$scope.products[index].quantity;
        // }

        my_item.paid_amount = my_item.price*1;
        my_item.quantity = 1;
        products.push(my_item);

        $scope.product = {
            canteen_item_id:0,
            item_name:'',
            quantity:1,
        }; 

        $scope.products = products;
        var total_amount = 0;
        var total_item = 0;
        for (var i = 0; i < $scope.products.length; i++) {
            var el = $scope.products[i];
            total_amount = total_amount+el.paid_amount;
            total_item = total_item+el.quantity;
        }

        $scope.formData.total_amount = total_amount;
        $scope.formData.total_item = total_item;
        $scope.productname = '';
        $("#productname").focus();

    }

    $scope.removeItem = (index) => {
        $scope.product = $scope.products[index];
        $scope.formData.total_amount = $scope.formData.total_amount - $scope.product.paid_amount;
        $scope.formData.total_item = $scope.products.length -1;
        $scope.products.splice(index,1);

    }

    $scope.handleKeyPress = function(event) {
        if (event.which === 13) {
            $scope.onAddProdcut();
            if ($scope.scannedValue.trim()) {
                $scope.scannedValue = '';
            }
        } else {
            $scope.scannedValue = ($scope.scannedValue || '') + event.key;
        }
    };
    // $scope.onAddProdcut = () => {
    //     let products = $scope.products;
    //     var my_item = $scope.canteen_items.find(item => item.canteen_item_id == $scope.product.canteen_item_id);
    //     let index = -1;
    //     if(products.length > 0){
    //         index = products.find(item => item.canteen_item_id == $scope.product.canteen_item_id);
    //     }

    //     if (index == -1) {
    //         my_item.paid_amount = my_item.price*$scope.product.quantity;
    //         my_item.quantity = $scope.product.quantity;
    //         products.push(my_item);
    //     } else {
    //         $scope.products[index].quantity += $scope.product.quantity;
    //         $scope.products[index].paid_amount = my_item.price*$scope.products[index].quantity;
    //     }

    //     $scope.product = {
    //         canteen_item_id:0,
    //         item_name:'',
    //         quantity:'',
    //     }; 

    //     $scope.products = products;

    //     var total_amount = 0;
    //     var total_item = 0;
    //     for (var i = 0; i < $scope.products.length; i++) {
    //         var el = $scope.products[i];
    //         total_amount = total_amount+el.paid_amount;
    //         total_item = total_item+el.quantity;
    //     }

    //     $scope.formData.total_amount = total_amount;
    //     $scope.formData.total_item = total_item;

    // }

});
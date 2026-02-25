app.controller('ExpenseCtrl', function($scope , $http, $timeout , DBService, Upload) {
    $scope.expense_id = 0;
    $scope.total_expense = 0;
    $scope.loading = false;
    
    $scope.index = '';
    $scope.expenses = [];
    $scope.clients =[];
    
    $scope.formData = {
        total_amount:0,
        date:'',
        client_id:'',
        multiple_expense:[{
            expense_account: 1,
            gst:0,
        }]};

    $scope.single_expense = {
        gst:0,
    };    

    $scope.expense_accounts =[];
    $scope.searchData = { };


    $scope.init = function(){
        $scope.loading = true;
        DBService.postCall($scope.searchData,'/api/expenses/init').then(function(data){
            if(data.success){
                $scope.expenses = data.expenses;   
                $scope.expense_accounts =data.expense_accounts;
           
                $scope.clients =data.clients;
            }
            $scope.loading = false;
        }); 
    }
    $scope.onSearch = function(){
        console.log($scope.searchData);
        $scope.init();
    }
    $scope.clearFilter = function(){
        $scope.searchData = { };
        $scope.init();
    }

    $scope.edit = function(){
        DBService.postCall({expense_id:$scope.expense_id},'/api/expenses/edit').then(function(data){
            if (data.success) {
                console.log(data);
                $scope.expense_accounts =data.expense_accounts;
                $scope.clients =data.clients;

                if (data.expense) {
                    $scope.formData= data.expense;
                }
            }
        });
    }

    $scope.viewExpense = function(index){
        $scope.expense = $scope.expenses[index];
        console.log($scope.expense);
        var id = angular.element("#myModal");
        if (id) {
            id.modal("show");
        }

    }

    $scope.onSubmit = function(){
        $scope.processing = true;
        console.log($scope.formData);
        DBService.postCall($scope.formData,'/api/expenses/store').then(function(data){
            if (data.success) {
                alert(data.message);
                window.location = base_url + '/admin/expenses';
            }else{
                alert(data.message);
            }
            $scope.processing = false;
        });
    }


    $scope.duplicate = function(){
        $scope.formData.multiple_expense.push(JSON.parse(JSON.stringify(
            $scope.formData.multiple_expense[$scope.formData.multiple_expense.length - 1]
        )));

        $scope.calAllSum();
    }


    $scope.uploadFile = function (file,name,obj) {
        if(file){

            obj.uploading = true;
            var url = base_url+'/admin/uploadFile';
            Upload.upload({
                url: url,
                data: {
                    media: file
                }
            }).then(function (resp) {
                if(resp.data.success){
                    obj[name] = resp.data.media;

                } else {
                    alert(resp.data.message);
                }
                obj.uploading = false;
                console.log(resp.data.media);

            }, function (resp) {
                
                console.log('Error status: ' + resp.status);
                obj.uploading = false;

            }, function (evt) {
                
            });
        }
    }

    $scope.removeFileX = function(){
        $scope.single_expense.attachment = '';
    }


    $scope.deleteExpense = function(expense,index){
        $scope.expense_id= expense.id;
        if (confirm("Are You Sure ?") == true) {
            DBService.getCall('/api/expenses/delete/'+$scope.expense_id).then(function(data){
                if (data.success) {
                    alert(data.message);
                    $scope.expenses.splice(index,1);

                }else{
                    alert(data.message);
                }
            });
        };
    }

    $scope.calAllSum = () => {
        const totalAmount = $scope.formData.multiple_expense.reduce((sum, item) => sum + item.amount, 0);
        const total_amount_gst = $scope.formData.multiple_expense.reduce((sum, item) => sum + item.gst, 0);

        console.log(totalAmount+'tt some',total_amount_gst+'gst some');
        console.log(totalAmount,total_amount_gst);
        $scope.formData.total_amount = totalAmount+total_amount_gst;
    }

});
app.controller('IncomeCtrl', function($scope , $http, $timeout , DBService, Upload) {
    $scope.income_id = 0;
    $scope.total_income = 0;
    $scope.index = '';
    $scope.incomes = [];
    $scope.loading = false;
    $scope.formData = {
        date:'',
        client_id:'',
        c_services:[],
        total_amount: 0,
        back_balance: 0,
    };

    // $scope.c_services = [];
   
    $scope.searchData = { };
    $scope.clients = [];


    $scope.init = function(){
        $scope.loading = true;
        DBService.postCall($scope.searchData,'/api/income/init').then(function(data){
            if(data.success){
                $scope.incomes = data.incomes;
                $scope.income_types = data.income_types;
                $scope.clients = data.clients;
                $scope.loading = false;
            }

        }); 
    }
    $scope.onSearch = function(){
        $scope.init();
    }
    $scope.clearFilter = function(){
        $scope.searchData = { };
        $scope.init();
    }
    $scope.income_types = [];

    $scope.edit = function(){
        $scope.loading = true;
        DBService.postCall($scope.formData,'/api/income/edit').then(function(data){
            if (data.success) {
                $scope.clients = data.clients;               
                $scope.formData = data.formData;
                $scope.calAllSum();
                $scope.loading = false;
            }
        });
    }

    $scope.changeDate = function(){
        $scope.loading = true;
        $scope.edit();
    }

    $scope.viewIncome = function(index){
        $scope.income = $scope.incomes[index];
        console.log($scope.income);
        var id = angular.element("#myModal");
        if (id) {
            id.modal("show");
        }

    }

    $scope.onSubmit = function(){
        $scope.processing = true;
        // if($scope.formData.multiple_income[0].amount == null){
        //     alert("Please add at least one income");
        //     return;
        // }
        DBService.postCall($scope.formData,'/api/income/store').then(function(data){
            if (data.success) {
                alert(data.message);
                window.location = base_url + '/admin/income';
            }else{
                alert(data.message);
            }
            $scope.processing = false;
        });
    }
    $scope.duplicate = function(){
        $scope.formData.multiple_income.push(JSON.parse(JSON.stringify(
            $scope.formData.multiple_income[$scope.formData.multiple_income.length - 1]
        )));
    }

    $scope.uploadFile = function (file,name,obj) {
        if(file){

            obj.uploading = true;
            var url = base_url+'/admin/uploadFile';
            Upload.upload({
                url: url,
                data: {
                    media: file
                }
            }).then(function (resp) {
                if(resp.data.success){
                    obj[name] = resp.data.media;

                } else {
                    alert(resp.data.message);
                }
                obj.uploading = false;
                console.log(resp.data.media);

            }, function (resp) {
                
                console.log('Error status: ' + resp.status);
                obj.uploading = false;

            }, function (evt) {
                
            });
        }
    }

    $scope.removeFileX = function(){
        $scope.single_income.attachment = '';
    }


    $scope.deleteIncome = function(income,index){
        $scope.income_id= income.id;
        if (confirm("Are You Sure ?") == true) {
            DBService.getCall('/api/income/delete/'+$scope.income_id).then(function(data){
                if (data.success) {
                    alert(data.message);
                    $scope.incomes.splice(index,1);

                }else{
                    alert(data.message);
                }
            });
        };
    }

    // $scope.calAllSum = () => {

    //     const cash_amount = $scope.formData.c_services.reduce((sum, item) => (item.cash_amount != '') && sum + (parseInt(item.cash_amount))   , 0);
    //     const upi_amount = $scope.formData.c_services.reduce((sum, item) => (item.upi_amount != '') && sum + (parseInt(item.upi_amount)), 0);

    //     const total_amount = $scope.formData.c_services.reduce((sum, item) => (item.total_amount != '') && sum + (parseInt(item.total_amount)), 0);
    //     $scope.formData.total_amount = total_amount;
    //     $scope.formData.cash_amount = cash_amount;
    //     $scope.formData.upi_amount = upi_amount;
    //     $scope.formData.all_total= parseInt($scope.formData.total_amount)+parseInt($scope.formData.back_balance);
    // }

    $scope.calAllSum = () => {
        const cash_amount = $scope.formData.c_services.reduce((sum, item) => sum + (Number(item.cash_amount) || 0), 0);
        const upi_amount = $scope.formData.c_services.reduce((sum, item) => sum + (Number(item.upi_amount) || 0), 0);
        const total_amount = $scope.formData.c_services.reduce((sum, item) => sum + (Number(item.total_amount) || 0), 0);
        
        $scope.formData.cash_amount = cash_amount;
        $scope.formData.upi_amount = upi_amount;
        $scope.formData.total_amount = total_amount;
        
        const back_balance = Number($scope.formData.back_balance) || 0;
        $scope.formData.all_total = total_amount + back_balance;
    };

    $scope.calAllSumOther = (index) => {

        const cash_amount = $scope.formData.c_services.reduce((sum, item) => (item.service_id == 6 && item.cash_amount !='') ? sum + parseInt(item.cash_amount) : 0 , 0);
        const upi_amount = $scope.formData.c_services.reduce((sum, item) => (item.service_id == 6 && item.upi_amount !='') ? sum + parseInt(item.upi_amount) : 0 , 0);
        const total_amount = $scope.formData.c_services.reduce((sum, item) => item.service_id == 6 ? parseInt(upi_amount)+parseInt(cash_amount) :0 , 0); 

        $scope.formData.c_services[index].total_amount = total_amount;

        const s_total_amount = $scope.formData.c_services.reduce((sum, item) => sum + (parseInt(item.total_amount)), 0);
        const s_cash_amount = $scope.formData.c_services.reduce((sum, item) => sum + (parseInt(item.cash_amount)), 0);
        const s_upi_amount = $scope.formData.c_services.reduce((sum, item) => sum + (parseInt(item.upi_amount)), 0);
       

        $scope.formData.total_amount = s_total_amount;
        $scope.formData.cash_amount = s_cash_amount;
        $scope.formData.upi_amount = s_upi_amount;
        $scope.formData.all_total= parseInt($scope.formData.total_amount)+parseInt($scope.formData.back_balance);
    }


});
// app.controller('IncomeCtrl', function($scope , $http, $timeout , DBService, Upload) {
//     $scope.income_id = 0;
//     $scope.total_income = 0;
//     $scope.index = '';
//     $scope.incomes = [];
//     $scope.loading = false;
//     $scope.formData = {all_total:0,total_amount:0,back_balance:0,multiple_income:[{amount:''

//     }]};

//     $scope.single_income = {
//         amount:'',
//     };    

//     $scope.income_accounts =[];
//     $scope.searchData = { };
//     $scope.clients = [];


//     $scope.init = function(){
//         $scope.loading = true;
//         DBService.postCall($scope.searchData,'/api/income/init').then(function(data){
//             if(data.success){
//                 $scope.incomes = data.incomes;
//                 $scope.income_types = data.income_types;
//                 $scope.clients = data.clients;
                
//             }

//             $scope.loading = false;
//         }); 
//     }
//     $scope.onSearch = function(){
//         $scope.init();
//     }
//     $scope.clearFilter = function(){
//         $scope.searchData = { };
//         $scope.init();
//     }
//     $scope.income_types = [];

//     $scope.edit = function(){
//         DBService.postCall($scope.formData,'/api/income/edit').then(function(data){
//             if (data.success) {
//                 $scope.income_types = data.income_types; 
//                 $scope.clients = data.clients;               
//                 if (data.income) {
//                     $scope.formData= data.income;
//                 } else {
//                     $scope.formData = {client_id: data.client_id, date: data.date,all_total:0,total_amount:0,back_balance:0,multiple_income:[{amount:''}]};
//                 }
//             }
//         });
//     }

//     $scope.changeDate = function(){
//         $scope.edit();
//     }

//     $scope.viewIncome = function(index){
//         $scope.income = $scope.incomes[index];
//         console.log($scope.income);
//         var id = angular.element("#myModal");
//         if (id) {
//             id.modal("show");
//         }

//     }

//     $scope.onSubmit = function(){
//         $scope.processing = true;
//         if($scope.formData.multiple_income[0].amount == null){
//             alert("Please add at least one income");
//             return;
//         }
//         DBService.postCall($scope.formData,'/api/income/store').then(function(data){
//             if (data.success) {
//                 alert(data.message);
//                 window.location = base_url + '/admin/income';
//             }else{
//                 alert(data.message);
//             }
//             $scope.processing = false;
//         });
//     }
//     $scope.duplicate = function(){
//         $scope.formData.multiple_income.push(JSON.parse(JSON.stringify(
//             $scope.formData.multiple_income[$scope.formData.multiple_income.length - 1]
//         )));
//     }

//     $scope.uploadFile = function (file,name,obj) {
//         if(file){

//             obj.uploading = true;
//             var url = base_url+'/admin/uploadFile';
//             Upload.upload({
//                 url: url,
//                 data: {
//                     media: file
//                 }
//             }).then(function (resp) {
//                 if(resp.data.success){
//                     obj[name] = resp.data.media;

//                 } else {
//                     alert(resp.data.message);
//                 }
//                 obj.uploading = false;
//                 console.log(resp.data.media);

//             }, function (resp) {
                
//                 console.log('Error status: ' + resp.status);
//                 obj.uploading = false;

//             }, function (evt) {
                
//             });
//         }
//     }

//     $scope.removeFileX = function(){
//         $scope.single_income.attachment = '';
//     }


//     $scope.deleteIncome = function(income,index){
//         $scope.income_id= income.id;
//         if (confirm("Are You Sure ?") == true) {
//             DBService.getCall('/api/income/delete/'+$scope.income_id).then(function(data){
//                 if (data.success) {
//                     alert(data.message);
//                     $scope.incomes.splice(index,1);

//                 }else{
//                     alert(data.message);
//                 }
//             });
//         };
//     }

//     $scope.removeFileX = function(){
//         $scope.single_income.attachment = '';
//     }


//     $scope.addMore = function(){
//         $scope.formData.multiple_income.push(JSON.parse(JSON.stringify($scope.single_income)));
//     }

//     $scope.calAllSum = () => {
//         const totalAmount = $scope.formData.multiple_income.reduce((sum, item) => sum + item.amount, 0);
//         $scope.formData.total_amount = totalAmount;
//         $scope.formData.all_total= parseInt($scope.formData.total_amount)+parseInt($scope.formData.back_balance);
//     }


// });

app.controller('SummaryCtrl', function($scope , $http, $timeout , DBService, Upload) {

    $scope.loading = false;
    $scope.searchData = { };
    $scope.income = { 
        multiple_income: [],
    };
    $scope.expenses = [];
    $scope.total_expenses = 0;
    $scope.export_link = "";
    $scope.searchData.export = false;

    $scope.init = function(){
        $scope.loading = true;
        DBService.postCall($scope.searchData,'/api/summary/init').then(function(data){
            if(data.success){
                $scope.clients = data.clients;
                $scope.expenses = data.expenses;
                $scope.income = data.income;
                $scope.searchData.date = data.date;
                $scope.searchData.client_id = data.client_id;
                $scope.total_expenses = data.total_expenses;
                $scope.searchData.export = false;
                if(data.export_link){
                    $scope.export_link = data.export_link;
                    window.open(base_url+'/temp/'+data.export_link,'_blank');
                }
            }

            $scope.loading = false;
        }); 
    }
    $scope.onSearch = function(){
        $scope.init();
    }
    
    $scope.clearFilter = function(){
        $scope.searchData = { };
        $scope.init();
    }    

    $scope.export = function(){
        $scope.searchData.export = true;
        $scope.init();
    }

});
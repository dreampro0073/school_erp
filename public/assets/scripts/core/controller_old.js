app.controller('cloackCtrl', function($scope , $http, $timeout , DBService, Upload,$interval) {
    $scope.loading = false;
    $scope.aadhar_flag = false;
    $scope.aadhar_fetch = false;
    $scope.aadhar_upload_flag = false;
    $scope.newAadharFlag = false;
    $scope.users = [];

    $scope.filter = {
        page_no:1,
        export:0,
    };

    $scope.aadhar_details = {};
    $scope.formData = {
        name:'',
        mobile:"",
        total_amount:0,
        paid_amount:0,
        balance_amount:0,
        hours_occ:0,
        check_in:'',
        check_out:'',
        no_of_bag:0,
        no_of_day:'',
    };
    
    $scope.type = 0;
    $scope.entry_id = 0;
    $scope.check_shift = "";
    $scope.pay_types = [];
    $scope.days = [];
    $scope.cloak_first_rate = 0;
    $scope.cloak_second_rate = 0;
    $scope.excel_loading = '';
    $scope.d_count = 0;
    $scope.productName = "";
    $scope.init = function () {
        $scope.aadhar_fetch = false;
        $scope.aadhar_flag = false;
        $scope.newAadharFlag = false;
        DBService.postCall($scope.filter, '/api/cloak-rooms/init/'+$scope.type).then((data) => {
            if (data.success) {
                $scope.pay_types = data.pay_types;
                $scope.l_entries = data.l_entries;
                $scope.days = data.days;
                $scope.cloak_first_rate = data.rate_list.first_rate;
                $scope.cloak_second_rate = data.rate_list.second_rate;
                $scope.d_count = data.d_count;
                $scope.updateCheckoutClass();
                $scope.users = data.users;
                if(data.excel_link){
                    $scope.excel_loading = false;
                    window.open(data.excel_link,'_blank');
                }
            }
        });
        $scope.productName = '';
        $("#productName").focus();
    }
    $scope.getData = (page) => {
        $scope.filter.page_no = $scope.filter.page_no + page;
        $scope.init();
        // console.log($scope.filter);
    }
    $scope.export = () => {
        $scope.type = 1;
        if($scope.filter.from_date !='' && $scope.filter.to_date !=''){
            $scope.filter.export = 1;
            $scope.excel_loading = true;
            $scope.init();
        }else{
            alert("Please select both date");
        }   
    }
    $scope.filterClear = function(){
        $scope.filter = {
            page_no:1,
            export:0,
        };
        $scope.init();
    }

    $scope.edit = function(entry_id){
        $scope.entry_id = entry_id;
        DBService.postCall({entry_id : $scope.entry_id}, '/api/cloak-rooms/edit-init').then((data) => {
            if (data.success) {
                $scope.formData = data.l_entry;
                $("#exampleModalCenter").modal("show");
            }  
        });
    }    

    $scope.checkoutCloak = function(entry_id){
        $scope.entry_id = entry_id;
        if(confirm("Are you sure?") == true){
             DBService.postCall({entry_id : $scope.entry_id}, '/api/cloak-rooms/checkout-init/1').then((data) => {
                if (data.timeOut) {
                    $scope.formData = data.l_entry;
                    $scope.formData.checkout_by = '';      
                    $("#checkoutCloakModel").modal("show");
                }else{
                    $scope.init(); 
                    $scope.filter.id = '';
                    alert(data.message);

                }
                
            });
        }
    }

    $scope.checkoutCloak1 = function(){
        DBService.postCall({productName : $scope.productName}, '/api/cloak-rooms/checkout-init/2').then((data) => {
            $scope.productName = '';
            if (data.timeOut) {
                $scope.formData = data.l_entry;
                $scope.entry_id = data.l_entry.id;
                $("#checkoutCloakModel").modal("show");
            }else{
                $scope.init(); 
                alert(data.message);

                $scope.filter.id = '';
                

            }
            
        });
    }

    $scope.handleKeyPress = function(event) {
        if (event.which === 13) {
            $scope.checkoutCloak1();
            if ($scope.scannedValue.trim()) {
                $scope.scannedValue = '';
            }
        } else {
            $scope.scannedValue = ($scope.scannedValue || '') + event.key;
        }
    };

    $scope.add = function(){
        $scope.entry_id = 0;
        $("#exampleModalCenter").modal("show");   
    }

    $scope.hideModal = () => {
        
        $scope.entry_id = 0;
        $scope.formData = {
            name:'',
            mobile:"",
            total_amount:0,
            paid_amount:0,
            balance_amount:0,
            hours_occ:0,
            check_in:'',
            check_out:'',
            no_of_bag:0,
            no_of_day:'',

        };
        $scope.aadhar_fetch = false;
        $scope.newAadharFlag = false;
        $scope.aadhar_flag = false;
        $("#exampleModalCenter").modal("hide");
        $("#checkoutCloakModel").modal("hide");
    }

    $scope.onSubmit = function () {
        $scope.loading = true;
        $scope.aadhar_fetch = false;
        $scope.aadhar_flag = false;
        $scope.newAadharFlag = false;

        DBService.postCall($scope.formData, '/api/cloak-rooms/store').then((data) => {

            if (data.success) {
                $scope.loading = false;

                $("#exampleModalCenter").modal("hide");
                $scope.entry_id = 0;
                $scope.formData = {
                    name:'',
                    mobile:"",
                    total_amount:0,
                    paid_amount:0,
                    balance_amount:0,
                    hours_occ:0,
                    check_in:'',
                    check_out:'',
                    no_of_bag:0,
                    no_of_day:'',
                };
                $scope.init();
                setTimeout(function(){
                    window.open(base_url+'/admin/cloak-rooms/print-unq/1/'+data.print_id,'_blank');
                }, 800);

            }
            $scope.loading = false;
        });
    }
    $scope.onCheckOut = function () {
        $scope.loading = true;
        DBService.postCall($scope.formData, '/api/cloak-rooms/checkout-store').then((data) => {
            if (data.success) {
                $("#checkoutCloakModel").modal("hide");
                $scope.entry_id = 0;
                $scope.formData = {
                    name:'',
                    mobile:"",
                    total_amount:0,
                    paid_amount:0,
                    balance_amount:0,
                    hours_occ:0,
                    check_in:'',
                    check_out:'',
                    no_of_bag:0,
                    no_of_day:'',
                };
                $scope.init();
                setTimeout(function(){
                    window.open(base_url+'/admin/cloak-rooms/print/'+data.id,'_blank');
                }, 800);
            }
            $scope.loading = false;
            $scope.aadhar_fetch = false;
            $scope.newAadharFlag = false; 
            $scope.aadhar_flag = false;
        });
    }

    $scope.changeAmount = function(){
        var amount = $scope.cloak_first_rate;
        

        if($scope.formData.no_of_day > 1){
            amount  = (amount + (($scope.formData.no_of_day-1)*$scope.cloak_second_rate));
            amount = amount*$scope.formData.no_of_bag;
        } else {
            amount = amount*$scope.formData.no_of_day*$scope.formData.no_of_bag;
        }

        if($scope.entry_id == 0){
            $scope.formData.paid_amount = amount;
        }else{
            $scope.formData.balance_amount = amount - $scope.formData.paid_amount;
        }
    }
    $scope.delete = function (id) {
        if(confirm("Are you sure?") == true){
            DBService.getCall('/api/cloak-rooms/delete/'+id).then((data) => {
                alert(data.message);
                $scope.init();
            });
        }
    }

    $scope.fetchAadhar = function(){
        if($scope.formData.aadhar_no){
            $scope.aadhar_fetch = true;
            DBService.postCall($scope.formData, '/api/aadhar/fetch').then((data) => {
                if(data.success){
                    $scope.newAadharFlag = data.newAadharFlag;
                    $scope.aadhar_details = data.details;
                    console.log(data.details);
                    if(!$scope.newAadharFlag){
                        $scope.formData.aadhar_no = $scope.aadhar_details.aadhar_no;
                        $scope.formData.name = $scope.aadhar_details.name;
                        $scope.formData.mobile_no = $scope.aadhar_details.mobile * 1;
                        if($scope.aadhar_details.front){
                            $scope.formData.aadhar_front = $scope.aadhar_details.front;
                            $scope.formData.aadhar_front_url = base_url+'/public/'+$scope.aadhar_details.front;
                            $scope.formData.aadhar_back = $scope.aadhar_details.back;
                            $scope.formData.aadhar_back_url = base_url+'/public/'+$scope.aadhar_details.back;
                        } else {
                           $scope.newAadharFlag = true; 
                        }
                    } 
                    $scope.aadhar_flag = true;
                } else {
                    $scope.aadhar_fetch = false;
                }
            });
        }
    }

    $scope.uploadFile = function (file, name, formData) {
        formData.pic_upload = true;
        var url = base_url + "/api/aadhar/file-upload";
        Upload.upload({
            url: url,
            data: {
                media: file,
                name:name,
            },
        }).then(
            function (resp) {
                if (resp.data.success) {
                    formData[name] = resp.data.path;
                    formData[name+'_url'] = resp.data.url;
                } else {
                    alert(resp.data.message);
                }
                formData.pic_upload = false;
            },
            function (resp) {
                console.log("Error status: " + resp.status);
                formData.pic_upload = false;
            },
            function (evt) {
                $scope.uploading_percentage =
                    parseInt((100.0 * evt.loaded) / evt.total) + "%";
            }
        );
    };

    $scope.removeFile = function (formData, name) {
        formData[name] = "";
    };    

    $scope.updateCheckoutDarkClass = function(){
        const milliseconds = new Date().getTime();
        const unixTimestamp = Math.floor(milliseconds / 1000);
        for (var i = 0; i < $scope.l_entries.length; i++) {
            $scope.l_entries[i].check_class = "";
            if($scope.l_entries[i].checkout_status == 0){
                if(unixTimestamp > $scope.l_entries[i].str_checkout_time){
                    $scope.l_entries[i].check_class = "t-danger";
                } else {
                    if((unixTimestamp+600) > $scope.l_entries[i].str_checkout_time){
                        $scope.l_entries[i].check_class = "t-warning";
                    } else {
                        $scope.l_entries[i].check_class = "t-info";
                    }
                }
            }
    
        }
    }    

    $scope.updateCheckoutClass = function(){
        const milliseconds = new Date().getTime();
        const unixTimestamp = Math.floor(milliseconds / 1000);
        for (var i = 0; i < $scope.l_entries.length; i++) {
            $scope.l_entries[i].check_class = "";
            if($scope.l_entries[i].checkout_status == 0){
                if(unixTimestamp > $scope.l_entries[i].str_checkout_time){
                    $scope.l_entries[i].check_class = "text-danger";
                } else {
                    if((unixTimestamp+600) > $scope.l_entries[i].str_checkout_time){
                        $scope.l_entries[i].check_class = "text-warning";
                    } else {
                        $scope.l_entries[i].check_class = "";
                    }
                }
            }
    
        }
    }

    var intervalPromise = $interval($scope.updateCheckoutClass, 12000);
    $scope.$on('$destroy', function() {
        if (intervalPromise) {
            $interval.cancel(intervalPromise);
        }
    }); 
});


app.controller('lockerCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading = false;
    $scope.formData = {
        name:'',
        mobile:"",
        paid_amount:0,
        no_of_day:'',
        locker_id:'',
    };

    $scope.filter = {};
    $scope.old_days = 0;
    $scope.entry_id = 0;

    $scope.check_shift = "";
    $scope.pay_types = [];
    $scope.avail_lockers = [];
    $scope.days = [];
    $scope.rate_list = {};

    $scope.sl_lockers = [];
    
    $scope.init = function () {
        
        DBService.postCall($scope.filter, '/api/locker/init').then((data) => {
            if (data.success) {
                $scope.pay_types = data.pay_types;
                $scope.l_entries = data.l_entries;
                $scope.avail_lockers = data.avail_lockers;
                $scope.days = data.days;
            }
        });
    }
    $scope.filterClear = function(){
        $scope.filter = {};
        $scope.init();
    }

    $scope.edit = function(entry_id){
        $scope.entry_id = entry_id;
        $scope.sl_lockers = [];
        DBService.postCall({entry_id : $scope.entry_id}, '/api/locker/edit-init').then((data) => {
            if (data.success) {
                $scope.formData = data.l_entry;
                $scope.sl_lockers = data.sl_lockers;
                $scope.rate_list = data.rate_list;
                $scope.old_days = data.l_entry.no_of_day;
                $("#exampleModalCenter").modal("show");
            }
            
        });
    }    

    $scope.checkoutLoker = function(entry_id){
        $scope.entry_id = entry_id;

        if(confirm("Are you sure?") == true){
             DBService.postCall({entry_id : $scope.entry_id}, '/api/locker/checkout-init').then((data) => {
                if (data.timeOut) {
                    $scope.formData = data.l_entry;
                    
                    $("#checkoutLokerModel").modal("show");
                }else{
                    $scope.init(); 
                }
                
            });
        }
    }

    $scope.add = function(){
        $scope.entry_id = 0;
        $scope.sl_lockers = [];
        DBService.postCall({entry_id : 0}, '/api/locker/edit-init').then((data) => {
            $scope.rate_list = data.rate_list;
            $("#exampleModalCenter").modal("show");  
        });  
    }

    $scope.hideModal = () => {
        $("#exampleModalCenter").modal("hide");
        $("#checkoutLokerModel").modal("hide");
        $scope.entry_id = 0;
        $scope.formData = {
            name:'',
            mobile:"",
            total_amount:0,
            paid_amount:0,
            balance_amount:0,
        };
    }

    $scope.onSubmit = function () {
        $scope.loading = true;
        if($scope.sl_lockers.length == 0){
            alert('Please select at least one locker');
            return;
        }

        $scope.formData.sl_lockers = $scope.sl_lockers;
        DBService.postCall($scope.formData, '/api/locker/store').then((data) => {
            if (data.success) {
                $scope.loading = false;

                $("#exampleModalCenter").modal("hide");
                $scope.entry_id = 0;
                $scope.formData = {
                    name:'',
                    mobile:"",
                    paid_amount:0,
                    no_of_day:'',
                    locker_id:'',
                };
                $scope.init();
                setTimeout(function(){
                    window.open(base_url+'/admin/locker/print/'+data.id,'_blank');
                }, 800);

            }
            $scope.loading = false;
        });
    }
    $scope.onCheckOut = function () {
        $scope.loading = true;
        DBService.postCall($scope.formData, '/api/locker/checkout-store').then((data) => {
            if (data.success) {
                $("#checkoutLokerModel").modal("hide");
                $scope.entry_id = 0;
                $scope.formData = {
                    name:'',
                    mobile:"",
                    total_amount:0,
                    paid_amount:0,
                    balance_amount:0,
                    hours_occ:0,
                    check_in:'',
                    check_out:'',
                };
                $scope.init();
            }
            $scope.loading = false;
        });
    }

    $scope.changeAmount = function(){
        var total_amount = 0;
        var amount = $scope.rate_list.first_rate;
        if($scope.formData.no_of_day > 1){
            amount  = (amount + (($scope.formData.no_of_day-1)*$scope.rate_list.second_rate));
        }
        amount = $scope.sl_lockers.length*amount;
        if($scope.entry_id == 0){
            $scope.formData.paid_amount = amount;
               
        }else{
            $scope.formData.balance_amount = amount - $scope.formData.paid_amount;
        }
    }

    $scope.delete = function (id) {
        if(confirm("Are you sure?") == true){
            DBService.getCall('/api/locker/delete/'+id).then((data) => {
                alert(data.message);
                $scope.init();
            });
        }
    }

    $scope.insLocker = (locker_id) => {
        let idx = $scope.sl_lockers.indexOf(locker_id);
        if(idx == -1){
            $scope.sl_lockers.push(locker_id);
        }else{
            $scope.sl_lockers.splice(idx,1);
        }
        $scope.changeAmount();
    }
});
app.controller('massageCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading = false;
    $scope.formData = {
        paid_amount:0,
        time_period:'',
        no_of_person:1,

    };

    $scope.filter = {};
    $scope.m_id = 0;
    $scope.m_entries = [];
    $scope.rate_list ={};
 
    $scope.init = function () {
        
        DBService.postCall($scope.filter, '/api/massage/init').then((data) => {
            $scope.pay_types = data.pay_types;
            $scope.m_entries = data.m_entries;
            $scope.rate_list = data.rate_list;
        });
    }
    $scope.filterClear = function(){
        $scope.filter = {};
        $scope.init();
    }

    $scope.edit = function(m_id){
        $scope.m_id = m_id;
        DBService.postCall({m_id : $scope.m_id}, '/api/massage/edit-init').then((data) => {
            if (data.success) {
                $scope.formData = data.m_entry;
                $("#massageModal").modal("show");
            }
        });
    }
    $scope.add = function(){
        $("#massageModal").modal("show");
    }

    $scope.hideModal = () => {
        $("#massageModal").modal("hide");
        $scope.entry_id = 0;
        $scope.formData = {
            paid_amount:0,
            time_period:0,
            no_of_person:1,

        };
        $scope.init();
    }

    $scope.changeTime = function(){
        
        $scope.formData.paid_amount = 0;
        if($scope.formData.time_period == 10){
            $scope.formData.paid_amount = $scope.rate_list.first_rate*$scope.formData.no_of_person;
        }
        if($scope.formData.time_period == 20){
            $scope.formData.paid_amount = $scope.rate_list.second_rate*$scope.formData.no_of_person;
        }
    }

    $scope.onSubmit = function () {
        $scope.loading = true;
        DBService.postCall($scope.formData, '/api/massage/store').then((data) => {
            if (data.success) {


                $("#massageModal").modal("hide");
                $scope.entry_id = 0;
                $scope.formData = {
                    paid_amount:0,
                    time_period:0,
                    no_of_person:1,

                };
                $scope.init();
                setTimeout(function(){
                    window.open(base_url+'/admin/massage/print/'+data.id, '_blank')
                }, 800);
            }
            $scope.loading = false;
        });
    }

    $scope.delete = function (id) {
        if(confirm("Are you sure") == true){
            DBService.getCall('/api/massage/delete/'+id).then((data) => {
                alert(data.message);
                $scope.init();
            });
        }
       
    }

});
app.controller('sittingCtrl', function($scope , $http, $timeout , DBService, $interval) {
    $scope.loading = false;
    $scope.formData = {
        id:'',
        no_of_adults:0,
        no_of_baby_staff:0,
        no_of_children:0,
        name:'',
        mobile:"",
        total_amount:0,
        paid_amount:0,
        balance_amount:0,
        hours_occ:'',
    }; 
    $scope.last_hour = 1;
    $scope.filter = {};
    $scope.checkout_number = '';

    $scope.entry_id = 0;
    $scope.pay_types = [];
    $scope.hours = [];
    $scope.rate_list = {};
    $scope.checkout_process = false;
    $scope.productName= '';
    $scope.old_hr = 0;
    $scope.checkout_th = 0;

    // $scope.newEditCheckout = function(new_checkout_id){
    //     $scope.entry_id = new_checkout_id;
    //     if(confirm("Are you sure?") == true){
    //         $scope.setNullFormData();
    //         DBService.postCall({checkout_id : new_checkout_id}, '/api/sitting/checkout-new').then((data) => {
    //             if (data.success) {
    //                 alert(data.message);
    //                 $scope.init();
    //             }else{
    //                 $scope.formData = data.entry;
    //                 $scope.checkout_process = true;

    //                 setTimeout(function(){
    //                    $("#checkoutModal").modal("show");
    //                 }, 800);
    //             }
    //         });
    //     };

    // }

    $scope.setNullFormData = function(){
        $scope.formData = {
            id:'',
            no_of_adults:0,
            no_of_baby_staff:0,
            no_of_children:0,
            name:'',
            mobile:"",
            total_amount:0,
            paid_amount:0,
            balance_amount:0,
            hours_occ:'',
        }; 
        $scope.last_hour = 1;
        $scope.old_hr = 0;

    }

    $scope.init = function () {
        $scope.setNullFormData();
        DBService.postCall($scope.filter, '/api/sitting/init').then((data) => {
            if (data.success) {
                $scope.pay_types = data.pay_types;
                $scope.hours = data.hours;
                $scope.entries = data.entries;
                $scope.rate_list = data.rate_list;
                $scope.updateCheckoutClass();
            }
            $("#productName").focus();
        });
    }
    $scope.filterClear = function(){
        $scope.filter = {};
        $scope.init();
    }

    $scope.edit = function(entry_id){
        $scope.checkout_process = false;
        $scope.entry_id = entry_id;
        $scope.setNullFormData();
        DBService.postCall({entry_id : $scope.entry_id}, '/api/sitting/edit-init').then((data) => {
            if (data.success) {
                $scope.last_hour = data.sitting_entry.hours_occ;
                $scope.formData = data.sitting_entry;
                $scope.old_hr = data.sitting_entry.hours_occ;

                $("#exampleModalCenter").modal("show");
            }
            
        });
    }    

    // $scope.editCheckout = function(entry_id){
    //     $scope.entry_id = entry_id;
    //     if(confirm("Are you sure?") == true){
    //         $scope.setNullFormData();
    //         DBService.postCall({entry_id : $scope.entry_id}, '/api/sitting/checkout-init/1').then((data) => {
    //             if (data.success) {
    //                 alert("Successfully checkout!");
    //                 $scope.init();
    //             }else{
    //                 $scope.last_hour += data.sitting_entry.hours_occ;
    //                 $scope.formData = data.sitting_entry;
    //                 $scope.checkout_process = true;
    //                 $scope.formData.hours_occ = data.ex_hours+ $scope.formData.hours_occ;
    //                 $scope.changeAmount();

    //                 setTimeout(function(){
    //                    $("#checkoutModal").modal("show");
    //                 }, 800);
    //             }
                
    //         });
    //     }
    // }

    // $scope.editCheckout1 = function(){
    //     $checkout_loading = true;
    //     $scope.setNullFormData();
    //     DBService.postCall({productName : $scope.productName}, '/api/sitting/checkout-init/2').then((data) => {
    //         $scope.productName = '';
    //         $checkout_loading = false;
    //         if (data.success) {
    //             alert(data.message);
    //             $scope.filterClear();
    //         }else{
    //             $scope.last_hour += data.sitting_entry.hours_occ;
    //             $scope.formData = data.sitting_entry;
    //             $scope.entry_id = data.id;
    //             $scope.checkout_process = true;
    //             $scope.formData.hours_occ = data.ex_hours+ $scope.formData.hours_occ;
    //             $scope.changeAmount();

    //             setTimeout(function(){
    //                $("#checkoutModal").modal("show");
    //             }, 800);

                
    //         }
           
    //     });
    // }

    $scope.newEditCheckout = function(new_checkout_id){
        $scope.checkout_th = 1;
        $scope.entry_id = new_checkout_id;

        if(confirm("Are you sure?") == true){
            $scope.setNullFormData();
            DBService.postCall({checkout_id : new_checkout_id,checkout_th:$scope.checkout_th}, '/api/sitting/checkout-new/1').then((data) => {
                if (data.success) {
                    alert(data.message);
                    $scope.init();
                    $scope.checkout_th =0;
                }else{
                    $scope.formData = data.entry;
                    $scope.checkout_process = true;

                    setTimeout(function(){
                       $("#checkoutModal").modal("show");
                    }, 800);
                }
            });
        };

    }

    $scope.newEditCheckout1 = function(){
        $scope.checkout_th = 2;
        $scope.setNullFormData();
        DBService.postCall({productName : $scope.productName,checkout_th:$scope.checkout_th}, '/api/sitting/checkout-new/2').then((data) => {
            $scope.productName = '';
            $checkout_loading = false;
            if (data.success) {
                alert(data.message);
                $scope.init();
                $scope.checkout_th =0;

            }else{
                $scope.entry_id = data.entry.id;

                $scope.formData = data.entry;
                $scope.checkout_process = true;

                setTimeout(function(){
                   $("#checkoutModal").modal("show");
                }, 800);
            }
           
        });
    }

    $scope.handleKeyPress = function(event) {

        if (event.which === 13) {
            $scope.newEditCheckout1();
            if ($scope.scannedValue.trim()) {
                $scope.scannedValue = '';
            }
        } else {
            $scope.scannedValue = ($scope.scannedValue || '') + event.key;
        }
    };

    $scope.add = function(){
        $scope.entry_id = 0;
        $scope.setNullFormData();
        $scope.checkout_process = false;
        $("#exampleModalCenter").modal("show");    
    }

    $scope.hideModal = () => {
        $scope.entry_id = 0;
        $scope.checkout_process = false;
        $scope.setNullFormData();
        $("#exampleModalCenter").modal("hide");
        $("#checkoutModal").modal("hide");
    }

    $scope.onSubmit = function () {
        $scope.loading = true;
        DBService.postCall($scope.formData, '/api/sitting/store').then((data) => {
            if (data.success) {
                $("#exampleModalCenter").modal("hide");
                $("#checkoutModal").modal("hide");
                $scope.entry_id = 0;
                $scope.setNullFormData();
                $scope.last_hour = 1;
                $scope.init();
                setTimeout(function(){
                    window.open(base_url+'/admin/sitting/print-unq/1/'+data.print_id, '_blank');

                }, 800);
                $scope.checkout_process = false;

            } else {
                alert(data.message);
            }
            $scope.loading = false;
        });
    }

    $scope.onSubmitCheckout = function () {
        $scope.loading = true;
        $scope.formData.checkout_th = $scope.checkout_th;
        DBService.postCall($scope.formData, '/api/sitting/checkout-store').then((data) => {
            if (data.success) {
                $("#exampleModalCenter").modal("hide");
                $("#checkoutModal").modal("hide");
                $scope.entry_id = 0;
                $scope.new_checkout_id = 0;
                $scope.productName = '';
                $scope.setNullFormData();
                $scope.last_hour = 1;
                $scope.checkout_th = 0;
                $scope.init();
                setTimeout(function(){
                    window.open(base_url+'/admin/sitting/print-unq/2/'+data.print_id, '_blank');
                }, 800);
                $scope.checkout_process = false;

            } else {
                alert(data.message);
            }
            $scope.loading = false;
        });
    }
    $scope.changeAmount = function () {
        $scope.formData.total_amount = 0;
        if($scope.formData.hours_occ > 0){
            var hours = $scope.formData.hours_occ - 1; 
           
            if($scope.formData.no_of_adults > 0){
                $scope.formData.total_amount += $scope.rate_list.adult_rate * $scope.formData.no_of_adults;
                
                $scope.formData.total_amount += hours * $scope.rate_list.adult_rate_sec * $scope.formData.no_of_adults;
                console.log($scope.formData.total_amount);
            }
            if($scope.formData.no_of_children > 0){
                $scope.formData.total_amount += $scope.rate_list.child_rate * $scope.formData.no_of_children;
                
                $scope.formData.total_amount +=  hours * $scope.rate_list.child_rate_sec * $scope.formData.no_of_children;
                 console.log($scope.formData.total_amount);
            }
        }
        $scope.formData.balance_amount = $scope.formData.total_amount - $scope.formData.paid_amount;

        $scope.geValTime();

    }

    $scope.geValTime = function(){
          DBService.postCall({entry_id:$scope.entry_id,hours_occ:$scope.formData.hours_occ}, '/api/sitting/cal-check').then((data) => {
            if (data.success) { 
                $scope.formData.show_valid_up = data.show_valid_up;
                
            }else{

            }
        });  
    }

    $scope.updateCheckoutClass = function(){
        const milliseconds = new Date().getTime();
        const unixTimestamp = Math.floor(milliseconds / 1000);
        for (var i = 0; i < $scope.entries.length; i++) {
            $scope.entries[i].check_class = "";
            if($scope.entries[i].checkout_status == 0){
                if(unixTimestamp > $scope.entries[i].str_checkout_time){
                    $scope.entries[i].check_class = "t-danger";
                } else {
                    if((unixTimestamp+600) > $scope.entries[i].str_checkout_time){
                        $scope.entries[i].check_class = "t-warning";
                    } else {
                        $scope.entries[i].check_class = "t-info";
                    }
                }
            }

        }
    }

    var intervalPromise = $interval($scope.updateCheckoutClass, 12000);
    $scope.$on('$destroy', function() {
        if (intervalPromise) {
            $interval.cancel(intervalPromise);
        }
    });



    // $scope.checkoutAlert = function() {
    //     console.log('hello');
    //     DBService.postCall($scope.filter, '/api/sitting/checkout-alert').then((data) => {
    //         if (data.success) {
    //             $scope.speak(data.message);
    //         }
    //     });
    // }
    // setInterval($scope.checkoutAlert, 60000);

    // $scope.speak = function(message) {
    //     const utterance = new SpeechSynthesisUtterance(message);
    //     const voices = speechSynthesis.getVoices();
    //     utterance.voice = voices[0];
    //     utterance.rate = 0.8; 
    //     utterance.pitch = 1;
    //     utterance.volume = 1;

       
    //     speechSynthesis.speak(utterance);

    // }


    // $scope.delete = function (id) {
    //     if(confirm("Are you sure?") == true){
    //         DBService.getCall('/api/sitting/delete/'+id).then((data) => {
    //             alert(data.message);
    //             $scope.init();
    //         });
    //     }
       
    // }
});


app.controller('reclinerCtrl', function($scope , $http, $timeout , DBService, $interval) {
    $scope.loading = false;
    $scope.formData = {
        id:'',
        no_of_adults:0,
        no_of_baby_staff:0,
        no_of_children:0,
        name:'',
        mobile:"",
        total_amount:0,
        paid_amount:0,
        balance_amount:0,
        hours_occ:'',
    }; 
    $scope.last_hour = 1;
    $scope.filter = {};
    $scope.checkout_number = '';

    $scope.entry_id = 0;
    $scope.pay_types = [];
    $scope.hours = [];
    $scope.rate_list = {};
    $scope.checkout_process = false;
    $scope.productName= '';
    $scope.old_hr = 0;
    $scope.checkout_th = 0;
    $scope.avail_recliners =[];
    $scope.sl_recliners =[];

    $scope.setNullFormData = function(){
        $scope.formData = {
            id:'',
            no_of_adults:0,
            no_of_baby_staff:0,
            no_of_children:0,
            name:'',
            mobile:"",
            total_amount:0,
            paid_amount:0,
            balance_amount:0,
            hours_occ:'',
        }; 
        $scope.last_hour = 1;
        $scope.old_hr = 0;
        $scope.sl_recliners =[];

    }

    $scope.init = function () {
        $scope.setNullFormData();
        DBService.postCall($scope.filter, '/api/recliners/init').then((data) => {
            if (data.success) {
                $scope.pay_types = data.pay_types;
                $scope.hours = data.hours;
                $scope.entries = data.entries;
                $scope.rate_list = data.rate_list;
                $scope.avail_recliners = data.avail_recliners;
                $scope.updateCheckoutClass();
            }
            $("#productName").focus();
        });
    }
    $scope.filterClear = function(){
        $scope.filter = {};
        $scope.init();
    }

    $scope.edit = function(entry_id){
        $scope.checkout_process = false;
        $scope.entry_id = entry_id;
        $scope.setNullFormData();
        DBService.postCall({entry_id : $scope.entry_id}, '/api/recliners/edit-init').then((data) => {
            if (data.success) {
                $scope.last_hour = data.rec_entry.hours_occ;
                $scope.formData = data.rec_entry;
                $scope.old_hr = data.rec_entry.hours_occ;
                $scope.sl_recliners = data.sl_recliners;
                $("#exampleModalCenter").modal("show");
            }
            
        });
    }    
    $scope.newEditCheckout = function(new_checkout_id){
        $scope.checkout_th = 1;
        $scope.entry_id = new_checkout_id;

        if(confirm("Are you sure?") == true){
            $scope.setNullFormData();
            DBService.postCall({checkout_id : new_checkout_id,checkout_th:$scope.checkout_th}, '/api/recliners/checkout-new/1').then((data) => {
                if (data.success) {
                    alert(data.message);
                    $scope.init();
                    $scope.checkout_th =0;
                }else{
                    $scope.formData = data.entry;
                    $scope.checkout_process = true;

                    setTimeout(function(){
                       $("#checkoutModal").modal("show");
                    }, 800);
                }
            });
        };
    }

    $scope.newEditCheckout1 = function(){
        $scope.checkout_th = 2;
        $scope.setNullFormData();
        DBService.postCall({productName : $scope.productName,checkout_th:$scope.checkout_th}, '/api/recliners/checkout-new/2').then((data) => {
            $scope.productName = '';
            $checkout_loading = false;
            if (data.success) {
                alert(data.message);
                $scope.init();
                $scope.checkout_th =0;

            }else{
                $scope.entry_id = data.entry.id;

                $scope.formData = data.entry;
                $scope.checkout_process = true;

                setTimeout(function(){
                   $("#checkoutModal").modal("show");
                }, 800);
            }
           
        });
    }

    $scope.handleKeyPress = function(event) {

        if (event.which === 13) {
            $scope.newEditCheckout1();
            if ($scope.scannedValue.trim()) {
                $scope.scannedValue = '';
            }
        } else {
            $scope.scannedValue = ($scope.scannedValue || '') + event.key;
        }
    };

    $scope.add = function(){
        $scope.entry_id = 0;

        $scope.setNullFormData();
        $scope.checkout_process = false;
        $("#exampleModalCenter").modal("show");    
    }

    $scope.hideModal = () => {
        $scope.entry_id = 0;
        $scope.checkout_process = false;
        $scope.setNullFormData();
        $("#exampleModalCenter").modal("hide");
        $("#checkoutModal").modal("hide");
    }

    $scope.onSubmit = function () {
        if($scope.sl_recliners.length == 0){
            alert('Please select at least one Recliner');
            return;
        }

        $scope.formData.sl_recliners = $scope.sl_recliners;
        $scope.loading = true;
        DBService.postCall($scope.formData, '/api/recliners/store').then((data) => {
            if (data.success) {
                $("#exampleModalCenter").modal("hide");
                $("#checkoutModal").modal("hide");
                $scope.entry_id = 0;
                $scope.setNullFormData();
                $scope.last_hour = 1;
                $scope.init();
                setTimeout(function(){
                    window.open(base_url+'/admin/recliners/print-unq/1/'+data.print_id, '_blank');

                }, 800);
                $scope.checkout_process = false;

            } else {
                alert(data.message);
            }
            $scope.loading = false;
        });
    }

    $scope.onSubmitCheckout = function () {
        $scope.loading = true;
        $scope.formData.checkout_th = $scope.checkout_th;
        DBService.postCall($scope.formData, '/api/recliners/checkout-store').then((data) => {
            if (data.success) {
                $("#exampleModalCenter").modal("hide");
                $("#checkoutModal").modal("hide");
                $scope.entry_id = 0;
                $scope.new_checkout_id = 0;
                $scope.productName = '';
                $scope.setNullFormData();
                $scope.last_hour = 1;
                $scope.checkout_th = 0;
                $scope.init();
                setTimeout(function(){
                    window.open(base_url+'/admin/recliners/print-unq/2/'+data.print_id, '_blank');
                }, 800);
                $scope.checkout_process = false;

            } else {
                alert(data.message);
            }
            $scope.loading = false;
        });
    }
    $scope.changeAmount = function () {
        $scope.formData.total_amount = 0;
        if($scope.formData.hours_occ > 0){
            var hours = $scope.formData.hours_occ - 1; 
            $scope.formData.total_amount += $scope.rate_list.second_rate * $scope.sl_recliners.length;     
            $scope.formData.total_amount += hours * $scope.rate_list.second_rate * $scope.sl_recliners.length;
               
        }
        $scope.formData.balance_amount = $scope.formData.total_amount - $scope.formData.paid_amount;
        $scope.geValTime();

    }

    $scope.geValTime = function(){
          DBService.postCall({entry_id:$scope.entry_id,hours_occ:$scope.formData.hours_occ}, '/api/recliners/cal-check').then((data) => {
            if (data.success) { 
                $scope.formData.show_valid_up = data.show_valid_up;
                
            }else{

            }
        });  
    }

    $scope.updateCheckoutClass = function(){
        const milliseconds = new Date().getTime();
        const unixTimestamp = Math.floor(milliseconds / 1000);
        for (var i = 0; i < $scope.entries.length; i++) {
            $scope.entries[i].check_class = "";
            if($scope.entries[i].checkout_status == 0){
                if(unixTimestamp > $scope.entries[i].str_checkout_time){
                    $scope.entries[i].check_class = "t-danger";
                } else {
                    if((unixTimestamp+600) > $scope.entries[i].str_checkout_time){
                        $scope.entries[i].check_class = "t-warning";
                    } else {
                        $scope.entries[i].check_class = "t-info";
                    }
                }
            }

        }
    }

    var intervalPromise = $interval($scope.updateCheckoutClass, 12000);
    $scope.$on('$destroy', function() {
        if (intervalPromise) {
            $interval.cancel(intervalPromise);
        }
    });

     $scope.insRec = (locker_id) => {
        console.log(locker_id);
        let idx = $scope.sl_recliners.indexOf(locker_id);
        if(idx == -1){
            $scope.sl_recliners.push(locker_id);
        }else{
            $scope.sl_recliners.splice(idx,1);
        }
        $scope.changeAmount();
    }
});

app.controller('shiftCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading= false;
    $scope.sitting_data = [];
    $scope.cloak_data = [];
    $scope.canteen_data = [];
    $scope.massage_data = [];
    $scope.locker_data = [];
    $scope.recliner_data = [];
    $scope.scanning_data = [];
    $scope.pod_data = [];
    $scope.singal_cabin_data = [];
    $scope.double_bed_data = [];
    $scope.service_ids = [];
    $scope.clients = [];
    $scope.filter = {
        input_date:'',
        user_id:'',
        client_id:'',
    }

    $scope.clear = function(){
        $scope.filter = {
            input_date:'',
            user_id:'',
            client_id:'',
        }
        $scope.init();
    }
    $scope.changeFilter = function(){
        $scope.filter.user_id = '',
        $scope.init();
    }
    $scope.serach = function(){
        $scope.init();
    }

    $scope.users  = [];

    $scope.init = function () {
        $scope.loading = false;
        DBService.postCall($scope.filter, '/api/shift/init').then((data) => {
            if (data.success) { 

                $scope.users = data.users;                 
                $scope.clients = data.clients;                 
                $scope.service_ids = data.service_ids;                 
                
                $scope.sitting_data = data.sitting_data; 
                $scope.cloak_data = data.cloak_data; 
                $scope.canteen_data = data.canteen_data; 
                $scope.massage_data = data.massage_data;
                $scope.locker_data = data.locker_data;
                $scope.recliner_data = data.recliner_data;
                $scope.scanning_data = data.scanning_data;
                $scope.pod_data = data.pod_data;
                $scope.singal_cabin_data = data.singal_cabin_data;
                $scope.double_bed_data = data.double_bed_data;
               
                $scope.total_shift_upi = data.total_shift_upi ; 
                $scope.total_shift_cash = data.total_shift_cash ; 
                $scope.total_collection = data.total_collection ; 

                $scope.last_hour_upi_total = data.last_hour_upi_total ; 
                $scope.last_hour_cash_total = data.last_hour_cash_total ; 
                $scope.last_hour_total = data.last_hour_total ;
                $scope.change_data = data.chage_pay_type_data ;
                
                $scope.check_shift = data.check_shift ; 
                $scope.shift_date = data.shift_date ; 
            }
            $scope.loading = true;
        });
    }    

    
});

app.controller('userCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading = false;
    $scope.formData = {
        name:'',
        email:'',
        mobile:'',
        password:'',
        confirm_password:'',
    };
    $scope.filter = {};
    $scope.user_id = 0;
    $scope.users = [];
 
    $scope.init = function () {
        DBService.postCall($scope.filter, '/api/users/init').then((data) => {
            $scope.users = data.users;
        });
    }
    $scope.filterClear = function(){
        $scope.filter = {};
        $scope.init();
    }

    $scope.edit = function(user_id){
        $scope.user_id = user_id;
        DBService.postCall({user_id : $scope.user_id}, '/api/users/edit-init').then((data) => {
            if (data.success) {
                $scope.formData = data.user;
                $("#userModal").modal("show");
            }
        });
    }

    $scope.activeUser = function(user,index){
        if(confirm("Are you sure?") == true){
             DBService.postCall({user_id : user.id}, '/api/users/active-user').then((data) => {
                if (data.success) {
                    $scope.users[index].active = user.active == 0 ? 1 :0; 
                }
                alert(data.message);
            });
        }
    }

    $scope.hideModal = () => {
        $("#userModal").modal("hide");
        $scope.user_id = 0;
        $scope.formData = {
            name:'',
            email:'',
            mobile:'',
            password:'',
            confirm_password:'',
        };
        $scope.init();
    }

    $scope.add = () => {
        $("#userModal").modal("show");
        $scope.user_id = 0;
        $scope.formData = {
            name:'',
            email:'',
            mobile:'',
            password:'',
            confirm_password:'',
        };
    }

    $scope.onSubmit = function () {
        $scope.loading = true;
        DBService.postCall($scope.formData, '/api/users/store').then((data) => {
            if (data.success) {
                alert(data.message);
                $("#userModal").modal("hide");
                $scope.formData = {
                    name:'',
                    email:'',
                    mobile:'',
                    password:'',
                    confirm_password:'',
                };
                $scope.init();
            }else{
                alert(data.message);
            }
            $scope.loading = false;
        });
    }
});

app.controller('canteenItemsCtrl', function($scope , $http, $timeout , DBService) {
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
        DBService.postCall($scope.filter, '/api/canteen-items/init').then((data) => {
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
        DBService.postCall({canteen_item_id : $scope.canteen_item_id}, '/api/canteen-items/edit').then((data) => {
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
        DBService.postCall($scope.formData, '/api/canteen-items/store').then((data) => {
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
        DBService.postCall($scope.filter, '/api/canteen-items/stocks/init').then((data) => {
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
        DBService.postCall({canteen_item_stock_id : $scope.canteen_item_stock_id}, '/api/canteen-items/stocks/edit').then((data) => {
            if (data.success) {
                $scope.stockData = data.item_stock;
                $("#stockModal").modal("show");
            }
        });
    }

    $scope.onStockSubmit = function () {
        $scope.loading = true;
        $scope.stockData.canteen_item_id = $scope.canteen_item_id;
        DBService.postCall($scope.stockData, '/api/canteen-items/stocks/store').then((data) => {
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

app.controller('dailyEntryCtrl', function($scope , $http, $timeout , DBService) {
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
        DBService.postCall($scope.filter, '/api/daily-entries/init').then((data) => {
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
        DBService.postCall({entry_id : $scope.entry_id}, '/api/daily-entries/edit-init').then((data) => {
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

        DBService.postCall($scope.formData, '/api/daily-entries/store').then((data) => {
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

app.controller('cloackPenltyCollectCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading = false;
    $scope.formData = {
        id:0,
        no_of_bag:0,
        total_bag:0,
    };

    $scope.pData = {

    }
   
    $scope.init = function () {

        DBService.postCall($scope.filter, '/api/collect-cloak/init').then((data) => {
            if (data.success) {
                $scope.l_entries = data.l_entries;
                $scope.penlty_list = data.penlty_list;
                $scope.penalty_sum = data.penalty_sum;
                $scope.c_sum = data.c_sum;
            }
        });
    }
    $scope.filterClear = function(){
        $scope.filter = {};
        $scope.init();
    }

    $scope.collectCloak = function(item){
        $scope.formData.id = item.id;
        $scope.formData.total_bag = item.total_bag;
        $scope.formData.no_of_bag = item.no_of_bag;
        $("#exampleModalCenter").modal("show");
    }

    $scope.onSubmit = function () {
        $scope.loading = true;
        // console.log($scope.formData);return;
        DBService.postCall($scope.formData, '/api/collect-cloak/store').then((data) => {
            if (data.success) {
                alert(data.message);
                $("#exampleModalCenter").modal("hide");
                $scope.formData = {
                    id:0,
                    no_of_bag:0,
                    total_bag:0,
                };
                $scope.init();
            }else{
                alert(data.message);
            }
            $scope.loading = false;
        });
    }

    $scope.onPSubmit = function (pData) {
        $scope.ploading = true;
        $scope.pData = pData;
        DBService.postCall($scope.pData, '/api/collect-cloak/store-pen').then((data) => {
            if (data.success) {
                alert(data.message);
                $scope.pData = {
                   
                };
                $scope.init();
            }else{
                alert(data.message);
            }
            $scope.ploading = false;
        });
    }
    
});

app.controller('sittingCollectCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading = false;
    $scope.formData = {
        id:0,
        no_of_bag:0,
        total_bag:0,
    };
    
    $scope.checked = {
       
    };

    $scope.pData = {

    }
    $scope.entries =[];
   
    $scope.init = function () {

        DBService.postCall($scope.filter, '/api/collect-sitting/init').then((data) => {
            if (data.success) {
                $scope.entries = data.entries;
                $scope.e_entries_list = data.e_entries_list;
                $scope.c_sum = data.c_sum;
                $scope.e_ent_sum = data.e_ent_sum;
                $scope.checked = data.checked;
            }
        });
    }
    $scope.filterClear = function(){
        $scope.filter = {};
        $scope.init();
    }

    $scope.hideModal = () => {
       $("#exampleModalCenter").modal("hide");
    }

    $scope.collectSit = function(item){
        // console.log(item);
        $scope.formData.id = item.id;
        $scope.formData.total_no_of_adults = item.no_of_adults;
        $scope.formData.no_of_adults = item.no_of_adults;

        $scope.formData.total_no_of_children = item.no_of_children;
        $scope.formData.no_of_children = item.no_of_children;
        $scope.formData.hours_occ = item.hours_occ;
        $scope.formData.total_hours = item.total_hours;
        $("#exampleModalCenter").modal("show");
    }

    $scope.onSubmit = function () {
        $scope.loading = true;
        // console.log($scope.formData);return;
        DBService.postCall($scope.formData, '/api/collect-sitting/store').then((data) => {
            if (data.success) {
                alert(data.message);
                $("#exampleModalCenter").modal("hide");
                $scope.formData = {
                    id:0,
                    no_of_bag:0,
                    total_bag:0,
                };
                $scope.init();
            }else{
                alert(data.message);
            }
            $scope.loading = false;
        });
    }

    $scope.onPSubmit = function (pData) {
        $scope.ploading = true;
        $scope.pData = pData;
        DBService.postCall($scope.pData, '/api/collect-sitting/store-pen').then((data) => {
            if (data.success) {
                alert(data.message);
                $scope.pData = {
                   
                };
                $scope.init();
            }else{
                alert(data.message);
            }
            $scope.ploading = false;
        });
    }
    
});

app.controller('entryRoomCtrl', function($scope , $http, $timeout , DBService,$interval) {
    $scope.loading = false;
    $scope.formData = {
        name:'',
        mobile:"",
        paid_amount:0,
        no_of_day:'',
        locker_id:'',
        discount_amount:0,
    };
    $scope.type = 0;
    $scope.filter = {};

    $scope.entry_id = 0;

    $scope.check_shift = "";
    $scope.pay_types = [];
    $scope.avail_pods = [];
    $scope.avail_cabins = [];
    $scope.avail_beds = [];
    $scope.hours = [];

    $scope.sl_pods = [];
    $scope.sl_cabins = [];
    $scope.sl_beds = [];
    $scope.total_amount = 0;
    $scope.paid_amount = 0;
    $scope.balance_amount = 0;
    
    $scope.init = function () {
        DBService.postCall($scope.filter, '/api/rooms/init/'+$scope.type).then((data) => {
            if (data.success) {
                $scope.pay_types = data.pay_types;
                $scope.entries = data.entries;
                $scope.avail_pods = data.avail_pods;
                $scope.avail_cabins = data.avail_cabins;
                $scope.avail_beds = data.avail_beds;
                $scope.hours = data.hours;
                $scope.updateCheckoutClass();
            }
        });
    }
    $scope.filterClear = function(){
        $scope.filter = {};
        $scope.init();
    }

    $scope.edit = function(entry_id){
        $scope.entry_id = entry_id;
        $scope.sl_pods = [];
        $scope.sl_cabins = [];
        $scope.sl_beds = [];
        DBService.postCall({entry_id : $scope.entry_id}, '/api/rooms/edit-init').then((data) => {
            if (data.success) {
                console.log(data.l_entry.discount_amount+'hhhhh');
                $scope.formData = data.l_entry;
                $scope.total_amount = data.l_entry.total_amount;

                $scope.sl_pods = data.sl_pods;
                $scope.sl_cabins = data.sl_cabins;
                $scope.sl_beds = data.sl_beds;
                $("#exampleModalCenter").modal("show");
            }
            
        });
    }    

    $scope.checkoutLoker = function(entry_id){
        $scope.entry_id = entry_id;
        
        if(confirm("Are you sure?") == true){
            DBService.postCall({entry_id : $scope.entry_id}, '/api/rooms/checkout-init').then((data) => {
                if (data.timeOut) {
                    $scope.formData = data.l_entry;
                    
                    $("#checkoutModal").modal("show");
                }else{
                    $scope.init(); 
                }
                
            });
        }
    }

    $scope.add = function(){
        $scope.entry_id = 0;
        $scope.sl_pods = [];
        $scope.sl_cabins = [];
        $scope.sl_beds = [];
        $scope.formData = {
            name:'',
            mobile:"",
            paid_amount:0,
            total_amount:0,
            balance_amount:0,
            hours_occ:'',
            discount_amount:0,
            
        };
        $("#exampleModalCenter").modal("show");    
    }

    $scope.hideModal = () => {
        $("#exampleModalCenter").modal("hide");
        $("#checkoutModal").modal("hide");
        $scope.entry_id = 0;
        $scope.formData = {
            name:'',
            mobile:"",
            paid_amount:0,
            total_amount:0,
            balance_amount:0,
            hours_occ:'',
            discount_amount:0,
            
        };
        $scope.sl_pods = [];
        $scope.sl_beds = [];
        $scope.sl_cabins = [];
    }

    $scope.onSubmit = function () {

        $scope.formData.type = $scope.type;
       
        if($scope.type == 1 && $scope.sl_pods.length == 0 ){
            alert('Please select at least one pods');
            return;
        }

        if($scope.type == 2 && $scope.sl_cabins.length == 0 ){
            alert('Please select at least one single cabins');
            return;
        }

        if($scope.type == 3 && $scope.sl_beds.length == 0 ){
            alert('Please select at least one double bed');
            return;
        }

        $scope.loading = true;

        if($scope.type == 1){
            $scope.formData.sl_pods = $scope.sl_pods;
        }
        if($scope.type == 2){
            $scope.formData.sl_cabins = $scope.sl_cabins;
        }
        if($scope.type == 3){
            $scope.formData.sl_beds = $scope.sl_beds;
        }

        DBService.postCall($scope.formData, '/api/rooms/store/'+$scope.type).then((data) => {
            if (data.success) {
                $scope.loading = false;

                $("#exampleModalCenter").modal("hide");
                $scope.entry_id = 0;
                $scope.formData = {
                    name:'',
                    mobile:"",
                    paid_amount:0,
                    total_amount:0,
                    balance_amount:0,
                    hours_occ:'',
                    discount_amount:0,
                    
                };
                $scope.sl_pods = [];
                $scope.sl_beds = [];
                $scope.sl_cabins = [];
                $scope.init();
                setTimeout(function(){
                    window.open(base_url+'/admin/rooms/print/'+data.id,'_blank');
                }, 800);

            }
            $scope.loading = false;
        });
    }
    $scope.onCheckOut = function () {
        $scope.loading = true;
        DBService.postCall($scope.formData, '/api/rooms/checkout-store').then((data) => {
            if (data.success) {
                $("#checkoutModal").modal("hide");
                $scope.entry_id = 0;
                $scope.formData = {
                    name:'',
                    mobile:"",
                    total_amount:0,
                    paid_amount:0,
                    balance_amount:0,
                    hours_occ:0,
                    check_in:'',
                    check_out:'',
                    discount_amount:0,
                };
                $scope.init();
                setTimeout(function(){
                    window.open(base_url+'/admin/rooms/print/'+data.entry_id,'_blank');
                }, 800);
            }
            $scope.loading = false;
        });
    }



    $scope.changeAmount = () => {
        if($scope.type == 1){
            $scope.changeAmountPod();
        }

        if($scope.type == 2){
            $scope.changeAmountCabin();
        }

        if($scope.type == 3){
            $scope.changeAmountBed();
        }
    }
  
    $scope.changeAmountPod = () => {
        var total_amount = 0;
        if($scope.formData.hours_occ == 6){
           total_amount= $scope.sl_pods.length*299;
        }else if($scope.formData.hours_occ == 12){
           total_amount= $scope.sl_pods.length*499;
        }else if($scope.formData.hours_occ == 24){
           total_amount= $scope.sl_pods.length*799;
        }
        $scope.total_amount = total_amount;
        $scope.formData.total_amount = total_amount;

        if($scope.entry_id == 0){
            if($scope.formData.discount_amount > 0){
                $scope.formData.paid_amount = total_amount - $scope.formData.discount_amount;
            }else{
                $scope.formData.paid_amount = total_amount;
            }
            $scope.formData.balance_amount = total_amount - $scope.formData.paid_amount;   
        }else{
            $scope.formData.balance_amount = total_amount - ($scope.formData.paid_amount + $scope.formData.discount_amount); 

        }
    }

    $scope.changeAmountCabin = () => {
        var total_amount = 0;

        if($scope.formData.hours_occ == 6){
           total_amount = $scope.sl_cabins.length*399;
        }else if($scope.formData.hours_occ == 12){
           total_amount = $scope.sl_cabins.length*599;
        }else if($scope.formData.hours_occ == 24){
           total_amount = $scope.sl_cabins.length*1199;
        }

        // console.log()

        $scope.total_amount = total_amount;
        $scope.formData.total_amount = total_amount;

        if($scope.entry_id == 0){
            if($scope.formData.discount_amount > 0){
                $scope.formData.paid_amount = total_amount - $scope.formData.discount_amount;
            }else{
                $scope.formData.paid_amount = total_amount;
            }
            $scope.formData.balance_amount = total_amount - $scope.formData.paid_amount;   
        }else{
            $scope.formData.balance_amount = total_amount - ($scope.formData.paid_amount + $scope.formData.discount_amount); 

        }
        // $scope.balance_amount = $scope.formData.balance_amount;  
    }

    $scope.changeAmountBed = () => {

        var total_amount = 0;
        if($scope.formData.hours_occ == 6){
           total_amount = $scope.sl_beds.length*599;
        }else if($scope.formData.hours_occ == 12){
           total_amount = $scope.sl_beds.length*899;
        }else if($scope.formData.hours_occ == 24){
           total_amount = $scope.sl_beds.length*1699;
        }

        $scope.total_amount = total_amount;
        $scope.formData.total_amount = total_amount;

        if($scope.entry_id == 0){
            if($scope.formData.discount_amount > 0){
                $scope.formData.paid_amount = total_amount - $scope.formData.discount_amount;
            }else{
                $scope.formData.paid_amount = total_amount;
            }
            $scope.formData.balance_amount = total_amount - $scope.formData.paid_amount;   
        }else{
            $scope.formData.balance_amount = total_amount - ($scope.formData.paid_amount + $scope.formData.discount_amount); 

        }
        $scope.balance_amount = $scope.formData.balance_amount; 
    }

    $scope.delete = function (id) {
        if(confirm("Are you sure?") == true){
            DBService.getCall('/api/rooms/delete/'+id).then((data) => {
                alert(data.message);
                $scope.init();
            });
        }
    }

    $scope.insPods = (pod_id) => {
        let idx = $scope.sl_pods.indexOf(pod_id);
        if(idx == -1){
            $scope.sl_pods.push(pod_id);
        }else{
            $scope.sl_pods.splice(idx,1);
        }
        $scope.changeAmountPod();
    }

    $scope.insCabins = (cabin_id) => {
        let idx = $scope.sl_cabins.indexOf(cabin_id);
        if(idx == -1){
            $scope.sl_cabins.push(cabin_id);
        }else{
            $scope.sl_cabins.splice(idx,1);
        }
        $scope.changeAmountCabin();
    }

    $scope.insBeds = (bed_id) => {
        let idx = $scope.sl_beds.indexOf(bed_id);
        if(idx == -1){
            $scope.sl_beds.push(bed_id);
        }else{
            $scope.sl_beds.splice(idx,1);
        }
        $scope.changeAmountBed();
    }

    $scope.disAmount = () => {

        if($scope.formData.discount_amount > 0 && $scope.formData.total_amount > 0){
            if($scope.entry_id == 0){
                $scope.formData.paid_amount = $scope.total_amount - $scope.formData.discount_amount;
            }else{
                var str_data = (parseInt($scope.formData.paid_amount) + parseInt($scope.formData.discount_amount));
                
                $scope.formData.balance_amount = $scope.total_amount - str_data;
            }
            
        }
    }

    $scope.updateCheckoutClass = function(){
        const milliseconds = new Date().getTime();
        const unixTimestamp = Math.floor(milliseconds / 1000);
        for (var i = 0; i < $scope.entries.length; i++) {
            $scope.entries[i].check_class = "";
            if($scope.entries[i].checkout_status == 0){
                if(unixTimestamp > $scope.entries[i].str_checkout_time){
                    $scope.entries[i].check_class = "t-danger";
                } else {
                    if((unixTimestamp+1200) > $scope.entries[i].str_checkout_time){
                        $scope.entries[i].check_class = "t-warning";
                    } else {
                        $scope.entries[i].check_class = "t-info";
                    }
                }
            }

        }
    }

    var intervalPromise = $interval($scope.updateCheckoutClass, 12000);
    $scope.$on('$destroy', function() {
        if (intervalPromise) {
            $interval.cancel(intervalPromise);
        }
    });
});

app.controller('allRoomEntryCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading = false;
    
    $scope.filter = {};
    $scope.entries = [{}];
    $l_entry = {};

    $scope.init = function () {
        DBService.postCall($scope.filter, '/api/rooms/init-all').then((data) => {
            if (data.success) {
                $scope.entries = data.entries;  
            }
        });
    }
    $scope.initSingleEntry = function (entry_id) {
        // console.log(entry_id);
        DBService.postCall({entry_id:entry_id}, '/api/rooms/init-single-entry').then((data) => {
            if (data.success) {
                $scope.l_entry = data.l_entry; 
                $("#viewModal").modal("show");
            }
        });
    }
    $scope.hideModal = function(){
        $("#viewModal").modal("hide");
    }
    $scope.deleteEntry = function(entry_id,e_entry_id){
        console.log(entry_id,e_entry_id);
        DBService.getCall('/api/rooms/delete-e-entry/'+entry_id+'/'+e_entry_id).then((data) => {
            if (data.success) {
                $("#viewModal").modal("hide");
            }else{
                $("#viewModal").modal("hide");
                
            }
        });
    }
    $scope.filterClear = function(){
        $scope.filter = {};
        $scope.init();
    }
});
app.controller('clientSettingCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading = false;
    $scope.processing = false;
    $scope.filter = {};
    
    $scope.clients = [];

    $scope.init = function () {
        DBService.postCall($scope.filter, '/api/clients/init-amount-setting').then((data) => {
            if (data.success) {
                $scope.clients = data.clients;  
            }
        });
    }
    $scope.onSubmit = function () {
        $scope.processing = true;
        DBService.postCall({clients:$scope.clients}, '/api/clients/store-amount-setting').then((data) => {
            if (data.success) {
                $scope.init();
            }
            alert(data.message); 
            $scope.processing = false;
        });
    }

    $scope.shiftStatus = function () {
        $scope.processing = true;
        DBService.postCall($scope.filter, '/api/clients/shift-status').then((data) => {
            if(data.success){
                $scope.shift_rows = data.shift_rows;
            }
        });
    }
});
app.controller('scanningCtrl', function($scope , $http, $timeout , DBService) {
    $scope.loading = false;
    $scope.processing = false;
    $scope.filter = {};
    
    $scope.entries = [];
    $scope.incoming_types = [];
    $scope.item_types = [];
    $scope.rate_list = [];

    $scope.formData = {
        name:'',
        paid_amount:0,

    }

    $scope.init = function () {
        DBService.postCall($scope.filter, '/api/scanning/init').then((data) => {
            if (data.success) {
                $scope.entries = data.entries;  
                $scope.incoming_types = data.incoming_types;  
                $scope.item_types = data.item_types;  
                $scope.rate_list = data.rate_list;  
            }

            // console.log($scope.entries);
        });
    }
    $scope.add = () => {
        $("#exampleModalCenter").modal("show");
    }
    $scope.filterClear = function(){
        $scope.filter = {};
        $scope.init();
    }
    $scope.hideModal =() => {

        $("#exampleModalCenter").modal("hide");
        $scope.formData.name = '';
        $scope.formData.paid_amount = 0;
    }
    $scope.onSubmit = function () {
        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/scanning/store').then((data) => {
            if (data.success) {
                $scope.init();
                $scope.hideModal();

                 setTimeout(function(){
                    window.open(base_url+'/admin/scanning/print/'+data.print_id,'_blank');
                }, 800);
            }else{
                alert(data.message); 
            }
            $scope.processing = false;
        });
    }

    $scope.calAmount = () => {

        var my_item = $scope.rate_list.find(item => item.item_type_id == $scope.formData.item_type_id && item.incoming_type_id == $scope.formData.incoming_type_id);

        $scope.formData.paid_amount = $scope.formData.no_of_item*my_item.rate;


    }

});

// app.controller('dailyEntryCtrl', function($scope , $http, $timeout , DBService) {
//     $scope.loading = false;
//     $scope.formData = {
//         name:'',
//         mobile:'',
//         pay_type: 1,
//         total_amount: 0,
//         total_item:0,
//         products: [{demo:''}],

//     };

//     $scope.products = [];


//     // $scope.total_amount= 0;
//     $scope.filter = {};
//     $scope.entry_id = 0;
//     $scope.daily_entries = [];
//     $scope.canteen_items = [];

//     $scope.selectConfig = {
//         valueField: 'canteen_item_id',
//         labelField: 'item_name',
//         maxItems:1,
//         searchField: 'item_name',
//         create: false,
//         onInitialize: function(selectize){
              
//         }
//     }
//     $scope.init = function () {
//         DBService.postCall($scope.filter, '/api/daily-entries/init').then((data) => {
//             $scope.daily_entries = data.daily_entries;
//             $scope.canteen_items = data.canteen_items;
//         });
//     }

//     $scope.filterClear = function(){
//         $scope.filter = {};
//         $scope.init();
//     }

//     $scope.edit = function(entry_id){
//         $scope.entry_id = entry_id;
//         DBService.postCall({entry_id : $scope.entry_id}, '/api/daily-entries/edit-init').then((data) => {
//             if (data.success) {
//                 $scope.formData = data.s_entry;
//                 $("#exampleModalCenter").modal("show");
//             }
//         });
//     }

//     $scope.hideModal = () => {
//         $("#exampleModalCenter").modal("hide");
//         $scope.entry_id = 0;
//         $scope.formData = {
//             name:'',
//             mobile:'',
//             items:[],
//         };
//         $scope.init();
//     }

//     $scope.add = () => {
//         $("#exampleModalCenter").modal("show");
//         $scope.entry_id = 0;
//         $scope.formData = {
//             name:'',
//             mobile:'',
//             items:[],
//         };
//     }

//     $scope.onSubmit = function () {
//         $scope.loading = true;
//         $scope.formData.products = $scope.products;
//         if($scope.products.length == 0){
//             alert("Plese select at least one item.");
//             $scope.loading = false;
//             return;
//         }
//         DBService.postCall($scope.formData, '/api/daily-entries/store').then((data) => {
//             if (data.success) {
//                 alert(data.message);
//                 $("#exampleModalCenter").modal("hide");

//                 $scope.formData = {
//                     name:'',
//                     mobile:'',
//                     items:[],
//                 };
//                 $scope.products = [];
//                 $scope.init();
//                 setTimeout(function(){
//                     window.open(base_url+'/admin/daily-entries/print/'+data.entry_id,'_blank');
//                 }, 800);
//             }else{
//                 alert(data.message);
//             }
//             $scope.loading = false;
//         });
//     }

//     $scope.onAddProdcut = () => {
//         // console.log($scope.product);

//         // var total_amount = $scope.total_amount;
//         let products = $scope.products;

//         var my_item = null;
//         for (let i = 0; i < $scope.canteen_items.length; i++) {
//             let c_item = $scope.canteen_items[i];
//             if (c_item.canteen_item_id == $scope.product.canteen_item_id) {
//                 my_item = c_item;
//             }
//         }
//         let index = -1;

//         for (var i = 0; i < products.length; i++) {
//             let c_product = products[i];
//             if(c_product.canteen_item_id == $scope.product.canteen_item_id){
//                 index = i;
//             }
//         }

//         if (index == -1) {
//             my_item.paid_amount = my_item.price*$scope.product.quantity;
//             my_item.quantity = $scope.product.quantity;
//             products.push(my_item);
//         } else {
//             // total_amount += my_item.price*$scope.product.quantity;
//             $scope.products[index].quantity += $scope.product.quantity;
//             $scope.products[index].paid_amount = my_item.price*$scope.products[index].quantity;
//         }

//         $scope.product = {
//             canteen_item_id:0,
//             item_name:'',
//             quantity:'',
//         }; 

//         $scope.products = products;

//         var total_amount = 0;
//         var total_item = 0;
//         for (var i = 0; i < $scope.products.length; i++) {
//             var el = $scope.products[i];
//             total_amount = total_amount+el.paid_amount;
//             total_item = total_item+el.quantity;
//         }

//         $scope.formData.total_amount = total_amount;
//         $scope.formData.total_item = total_item;

//     }

//     $scope.editItem = (index) => {
//         $scope.product = $scope.products[index];
//         $scope.products.splice(index,1);
//     }

// });
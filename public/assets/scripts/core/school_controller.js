app.controller('schoolManagementCtrl', function($scope, DBService) {

    $scope.loading = false;
    $scope.list_loading = false;
    $scope.type = "classes";
    $scope.standards = [];
    $scope.sections = [];
    $scope.sessions = [];
    $scope.teachers = [];
    $scope.students = [];
    $scope.subjects = [];
    $scope.days = [];
    $scope.formData = {};
    $scope.isSidebarOpen = false;
    $scope.isEditMode = false;
    $scope.scheduleRows = [];
    $scope.edit_flag = false;


    $scope.schedule = {
        day_id: '8',
        standard_id: ''
    };

    $scope.addRow = function () {
        $scope.scheduleRows.push({
            standard_id: $scope.schedule.standard_id || '',
            section_id: '',
            subject_id: '',
            teacher_id: '',
            day_id: $scope.schedule.day_id || '',
            start_time: '',
            end_time: '',
            duration: '',
            remarks: ''
        });
    };

    $scope.removeRow = function (index) {
        $scope.scheduleRows.splice(index, 1);
    };

    $scope.init = function() {
        $scope.loading = true;

        DBService.postCall({ type: $scope.type }, '/api/admin/school/init').then(function(data) {
            if (data.success) {
                $scope.standards = data.standards || [];
                $scope.sections = data.sections || [];
                $scope.sessions = data.sessions || [];
                $scope.teachers = data.teachers || [];
                $scope.students = data.students || [];
                $scope.days = data.days || [];
            }
            $scope.edit_flag = false;
            $scope.loading = false;
        }, function() {
            $scope.loading = false;
        });
    };

    $scope.typeSchedule = function() {

        if (!$scope.schedule.day_id || !$scope.schedule.standard_id) {
            $scope.scheduleRows = [];
            return;
        }

        $scope.list_loading = true;

        DBService.postCall({
            type: 'schedule',
            day_id: $scope.schedule.day_id,
            standard_id: $scope.schedule.standard_id
        }, '/api/admin/school/schedule').then(function(data) {
            if (data.success) {
                $scope.scheduleRows = data.schedule || [];
                $scope.subjects = data.subjects || [];

                angular.forEach($scope.scheduleRows, function(row) {
                    row.standard_id = row.standard_id || $scope.schedule.standard_id;
                    row.day_id = row.day_id || $scope.schedule.day_id;
                });
            }

            $scope.list_loading = false;
        }, function() {
            $scope.list_loading = false;
        });
    };

    $scope.onTypeChange = function() {
        if ($scope.schedule.day_id && $scope.schedule.standard_id) {
            $scope.typeSchedule();
        } else {
            $scope.scheduleRows = [];
        }
    };

    $scope.saveSchedule = function() {

        if (!$scope.schedule.day_id || !$scope.schedule.standard_id) {
            alert("Please select day and standard");
            return;
        }

        if (!$scope.scheduleRows.length) {
            alert("Please add at least one row");
            return;
        }

        for (var i = 0; i < $scope.scheduleRows.length; i++) {
            var row = $scope.scheduleRows[i];

            // parent filters ko row me set karo
            row.day_id = $scope.schedule.day_id;
            row.standard_id = $scope.schedule.standard_id;

            if (!row.subject_id || !row.teacher_id || !row.start_time || !row.end_time) {
                alert("Please fill all required fields in row " + (i + 1));
                return;
            }
        }

        DBService.postCall({
            day_id: $scope.schedule.day_id,
            standard_id: $scope.schedule.standard_id,
            schedule: $scope.scheduleRows
        }, '/api/admin/school/schedule-store').then(function(data) {
            if (data.success) {
                alert(data.message || "Saved Successfully");
                $scope.typeSchedule();
            } else {
                alert(data.message || "Something went wrong");
            }
        });
    };
  
    // CLASSES

    $scope.typeClasses = function() {
        $scope.list_loading = true;
        DBService.postCall({type : 'classes'}, '/api/admin/school/classes').then(function(data) {
            if(data.success){
                $scope.dataSet = (data.classes || []).map(function(item) {
                    item.is_verified = (item.is_verified === null || item.is_verified === undefined || item.is_verified === '')
                        ? 0
                        : parseInt(item.is_verified, 10);

                    return item;
                });
                $scope.list_loading = false;
            }
        });
    };

    $scope.changeClassStatus = function (item, status) {
        var result = confirm("Are you sure?");
        if(result){
            DBService.postCall({entry_id : item.id, status : status}, '/api/admin/school/change-class-status').then(function(data) {
                alert(data.message);
                if(data.success){
                    item.is_verified = data.status;
                }
            });
        }
    }
    $scope.deleteClass = function (item) {
        var result = confirm("Are you sure?");

        if (result) {
            DBService.postCall({ id: item.id }, '/api/admin/school/class-delete')
            .then(function (data) {
                alert(data.message);

                if (data.success) {
                    var index = $scope.dataSet.indexOf(item);
                    if (index !== -1) {
                        $scope.dataSet.splice(index, 1);
                    }
                }
            });
        }
    }; 

    $scope.openAddModal = function () {
        $scope.isEditMode = false;
        $scope.formData = {};
        $scope.isSidebarOpen = true;
    };

    $scope.openEditModal = function(item) {
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
        $scope.formData = {};
    };


    $scope.submitClass = function () {
        DBService.postCall($scope.formData, '/api/admin/school/class-store').then(function(data) {
            alert(data.message);
            if(data.success){
                $scope.formData = {};
                $scope.resetForm();
                $scope.typeClasses();
            }
        });
    };
  

    $scope.typeExams = function() {
        $scope.list_loading = true;
        DBService.postCall({type : 'exams'}, '/api/admin/school/exams').then(function(data) {
            if(data.success){
                $scope.sections
                $scope.list_loading = false;
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
        $scope.list_loading = true;
        DBService.postCall({type : 'results'}, '/api/admin/school/results').then(function(data) {
            if(data.success){
                $scope.sections
                $scope.list_loading = false;
            }
        });
    };

    $scope.submitResult = function () {
        $http.post('/api/admin/school/class-store', $scope.formData).then(function (res) {
            alert(res.data.message);
            $scope.formData = {};
        });
    };

    $scope.resetForm = function () {
        $scope.formData = {};
        $scope.isEditMode = false;
        $scope.isSidebarOpen = false;
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

app.controller('classManagementCtrl', function($scope , DBService){
    $scope.class_id = 0;
    $scope.standard = {};
    $scope.dataList = [];
    $scope.fee_types = [];
    $scope.fee_frequencies = [];
    $scope.type = "fee";
    $scope.list_loading = false;
    $scope.isSidebarOpen = false;
    $scope.isEditMode = false;
    $scope.processing = false;
    $scope.formData = {
        class_id: '',
        fee_type_id: '',
        frequency_id: '',
        amount: ''
    };

    $scope.initClass = function() {
        $scope.list_loading = true;
        DBService.postCall({type : $scope.type, class_id : $scope.class_id}, '/api/admin/school/class-manage-init').then(function(data) {
            if(data.success){
                console.log($scope.standard)
                $scope.standard = data.standard; 
                $scope.dataList = data.dataList;
                $scope.fee_types = data.fee_types || [];
                $scope.fee_frequencies = data.fee_frequencies || [];
            }
            $scope.list_loading = false;
        }, function () {
            $scope.list_loading = false;
        });
    };

    $scope.editFeeRow = function(item) {
        if (!item || !item.id) {
            return;
        }

        $scope.processing = true;
        DBService.erpPostCall({id : item.id}, '/api/admin/school/fee-row-edit').then(function(data){
            $scope.processing = false;

            if(data.success){
                $scope.isEditMode = true;
                $scope.formData = data.row || {};
                $scope.formData.class_id = $scope.formData.class_id || $scope.class_id;
                $scope.formData.amount = ($scope.formData.amount === null || $scope.formData.amount === undefined || $scope.formData.amount === '')
                    ? ''
                    : Number($scope.formData.amount);
                $scope.isSidebarOpen = true;
            } else {
                alert(data.message || 'Data not found');
            }
        });
    };

    $scope.updateFeeRow = function() {
        $scope.processing = true;
        DBService.erpPostCall($scope.formData, '/api/admin/school/fee-row-store').then(function(data){
            $scope.processing = false;

            if(data.success){
                alert(data.message || 'Saved Successfully');
                $scope.closeSidebar();
                $scope.initClass();
            } else {
                if (data.errors) {
                    let firstError = Object.values(data.errors)[0][0];
                    alert(firstError);
                    $scope.errors = data.errors;
                } else {
                    alert(data.message || 'Something went wrong');
                }
            }
        });
    }    

    $scope.deleteFeeRow = function(item) {
        if (!item || !item.id) {
            return;
        }

        var result = confirm("Are you sure ?");
        if (!result) {
            return;
        }

        $scope.processing = true;
        DBService.erpPostCall({id : item.id}, '/api/admin/school/fee-row-delete').then(function(data){
            $scope.processing = false;

            alert(data.message || 'Deleted Successfully');
            if(data.success){
                $scope.initClass();
            }
        });
    }

    $scope.addFeeRow = function() {
        $scope.isEditMode = false;
        $scope.formData = {
            class_id: $scope.class_id,
            fee_type_id: '',
            frequency_id: '',
            amount: ''
        };
        $scope.isSidebarOpen = true;
    };

    $scope.closeSidebar = function () {
        $scope.isSidebarOpen = false;
        $scope.isEditMode = false;
        $scope.formData = {
            class_id: $scope.class_id,
            fee_type_id: '',
            frequency_id: '',
            amount: ''
        };
    };

    $scope.resetForm = function () {
        $scope.formData = {
            class_id: $scope.class_id,
            fee_type_id: '',
            frequency_id: '',
            amount: ''
        };
        $scope.isEditMode = false;
        $scope.isSidebarOpen = false;
    };

    $scope.addSubRow = function() {
        $scope.isEditMode = false;
        $scope.formData = {};
        $scope.isSidebarOpen = true;
    };

    $scope.editSubRow = function() {
        $scope.processing = true;
        DBService.erpPostCall($scope.formData, '/api/admin/school/sub-row-edit').then(function(data){
            $scope.isEditMode = true;
            $scope.formData = {};
            $scope.isSidebarOpen = true;
        });
    }

    $scope.updateSubRow = function() {
        $scope.processing = true;
        DBService.erpPostCall($scope.formData, '/api/admin/school/sub-row-store').then(function(data){
            $scope.processing = false;
            $scope.formData = data.row;
        });
    }    

    $scope.deleteSubRow = function() {
        $scope.processing = true;
        DBService.erpPostCall($scope.formData, '/api/admin/school/sub-row-delete').then(function(data){
            $scope.processing = false;
        });
    }

 });

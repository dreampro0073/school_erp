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
    $scope.isSidebarOpen = false;
    $scope.isEditMode = false;
    $scope.scheduleRows = [];

    $scope.addRow = function () {
        $scope.scheduleRows.push({
            standard_id: '',
            section_id: '',
            subject_id: '',
            teacher_id: '',
            day_id: '',
            start_time: '',
            end_time: '',
            duration: '',
            remarks: ''
        });
    };

    $scope.removeRow = function (index) {
        $scope.scheduleRows.splice(index, 1);
    };

    $scope.calculateDuration = function(row) {
        if(row.start_time && row.end_time){
            let start = new Date("1970-01-01 " + row.start_time);
            let end = new Date("1970-01-01 " + row.end_time);

            let diff = (end - start) / 60000;
            row.duration = diff + " mins";
        }
    };


    $scope.init = function(){
        $scope.loading = true;
        DBService.postCall({type : $scope.type}, '/api/admin/school/init').then(function(data) {
            if(data.success){
                $scope.standards = data.standards;
                $scope.sections = data.sections;
                $scope.sessions = data.sessions;
                $scope.teachers = data.teachers;
                $scope.students = data.students;
                $scope.days = data.days;
                $scope.loading = false;
            }
        });
    }

    $scope.typeSchedule = function() {
        $scope.list_loading = true;
        DBService.postCall({type : 'schedule'}, '/api/admin/school/schedule').then(function(data) {
            if(data.success){
                $scope.schedule = data.schedule;
                $scope.subjects = data.subjects;
                $scope.list_loading = false;
            }
        });
    }; 

    $scope.saveSchedule = function () {

        for(let row of $scope.scheduleRows){
            if(!row.standard_id || !row.subject_id || !row.teacher_id){
                alert("Please fill all required fields");
                return;
            }
        }

        DBService.postCall({ schedule: $scope.scheduleRows }, '/api/admin/school/schedule-store').then(function(data) {
            if(data.success){
                alert("Saved Successfully");
            }
        });
    };
  
    // CLASSES
    
    $scope.typeClasses = function() {
        $scope.list_loading = true;
        DBService.postCall({type : 'classes'}, '/api/admin/school/classes').then(function(data) {
            if(data.success){
                $scope.dataSet = data.classes;
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
    $scope.type = "students";
    $scope.list_loading = false;
    $scope.initClass = function() {
        $scope.list_loading = true;
        DBService.postCall({type : $scope.type, class_id : $scope.class_id}, '/api/admin/school/class-manage-init').then(function(data) {
            if(data.success){
                $scope.standard = data.standard; 
                $scope.dataList = data.dataList;
                $scope.list_loading = false;
            }
        });
    };
});

function confirmAction(title, text, onConfirm) {
    if (typeof window.Swal !== 'undefined' && typeof window.Swal.fire === 'function') {
        window.Swal.fire({
            title: title || 'Are you sure?',
            text: text || 'Please confirm this action.',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }).then(function(result) {
            if (result.isConfirmed && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
        return;
    }

    if (typeof window.swal === 'function') {
        window.swal({
            title: title || 'Are you sure?',
            text: text || 'Please confirm this action.',
            type: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes',
            cancelButtonText: 'Cancel'
        }, function(isConfirm) {
            if (isConfirm && typeof onConfirm === 'function') {
                onConfirm();
            }
        });
        return;
    }

    if (window.confirm(text || 'Please confirm this action.') && typeof onConfirm === 'function') {
        onConfirm();
    }
}

app.controller('dashboardCtrl', function($scope , DBService){
    $scope.cards = [];

    $scope.init = function() {
        DBService.postCall({}, '/api/dashboard/init').then(function(data) {
            if (!(data && data.success && data.stats)) {
                $scope.cards = [];
                return;
            }

            var stats = data.stats;
            $scope.cards = [
                {
                    key: 'users',
                    label: 'Total Users',
                    icon: 'student.svg',
                    gradient: 'bg-gradient-start-1',
                    bg: 'bg-info-focus',
                    mainValue: parseInt((stats.users && stats.users.total) || 0, 10),
                    active: parseInt((stats.users && stats.users.active) || 0, 10),
                    inactive: parseInt((stats.users && stats.users.inactive) || 0, 10),
                    url: base_url + '/super-admin/users/users'
                },
                {
                    key: 'clients',
                    label: 'Total Schools',
                    icon: 'teacher.svg',
                    gradient: 'bg-gradient-start-2',
                    bg: 'bg-primary-200',
                    mainValue: parseInt((stats.clients && stats.clients.total) || 0, 10),
                    active: parseInt((stats.clients && stats.clients.active) || 0, 10),
                    inactive: parseInt((stats.clients && stats.clients.inactive) || 0, 10),
                    url: base_url + '/super-admin/users/schools'
                },
                {
                    key: 'students',
                    label: 'Total Students',
                    icon: 'students.svg',
                    gradient: 'bg-gradient-start-3',
                    bg: 'bg-lilac-200',
                    mainValue: parseInt((stats.students && stats.students.total) || 0, 10),
                    active: parseInt((stats.students && stats.students.active) || 0, 10),
                    inactive: parseInt((stats.students && stats.students.inactive) || 0, 10),
                    url: base_url + '/super-admin/users/students'
                },
                {
                    key: 'teachers',
                    label: 'Total Teachers',
                    icon: 'money-recive.svg',
                    gradient: 'bg-gradient-start-4',
                    bg: 'bg-success-focus',
                    mainValue: parseInt((stats.teachers && stats.teachers.total) || 0, 10),
                    active: parseInt((stats.teachers && stats.teachers.active) || 0, 10),
                    inactive: parseInt((stats.teachers && stats.teachers.inactive) || 0, 10),
                    url: base_url + '/super-admin/users/teachers'
                },
                {
                    key: 'parents',
                    label: 'Total Parents',
                    icon: 'briefcase.svg',
                    gradient: 'bg-gradient-start-5',
                    bg: 'bg-danger-focus',
                    mainValue: parseInt((stats.parents && stats.parents.total) || 0, 10),
                    active: parseInt((stats.parents && stats.parents.active) || 0, 10),
                    inactive: parseInt((stats.parents && stats.parents.inactive) || 0, 10),
                    url: base_url + '/super-admin/users/parents'
                }
            ];
        });
    };
});

// *** attendanceCtrl ***

app.controller('attendanceCtrl', function($scope , DBService){
    var now = new Date();
    var month = String(now.getMonth() + 1).padStart(2, '0');
    var day = String(now.getDate()).padStart(2, '0');
    var today = now.getFullYear() + '-' + month + '-' + day;

    $scope.saving = false;
    $scope.statuses = [];
    $scope.defaultStatus = '';
    $scope.allItems = [];
    $scope.attendanceItems = [];
    $scope.historyRows = [];

    $scope.filters = {
        type: 'student',
        date: today,
        search: ''
    };

    $scope.historyFilter = {
        type: '',
        from_date: '',
        to_date: ''
    };

    $scope.init = function() {
        $scope.loadAttendance();
        $scope.loadHistory();
    };

    $scope.applyListFilter = function() {
        var search = ($scope.filters.search || '').toLowerCase().trim();
        $scope.attendanceItems = ($scope.allItems || []).filter(function(item) {
            if (!search) {
                return true;
            }

            var name = String(item.name || '').toLowerCase();
            var mobile = String(item.mobile || '').toLowerCase();
            return name.indexOf(search) !== -1 || mobile.indexOf(search) !== -1;
        });
    };

    $scope.resetSearch = function() {
        $scope.filters.search = '';
        $scope.applyListFilter();
    };

    $scope.loadAttendance = function() {
        DBService.postCall({
            type: $scope.filters.type,
            date: $scope.filters.date
        }, '/api/admin/attendance/init').then(function(data) {
            if (data && data.success) {
                $scope.filters.date = data.date || $scope.filters.date;
                $scope.statuses = data.statuses || [];
                $scope.defaultStatus = data.default_status || (($scope.statuses[0] && $scope.statuses[0].code) || '');
                $scope.allItems = data.items || [];
                $scope.allItems.forEach(function(item) {
                    if (!item.status) {
                        item.status = $scope.defaultStatus;
                    }
                });
            } else {
                $scope.statuses = [];
                $scope.defaultStatus = '';
                $scope.allItems = [];
            }
            $scope.applyListFilter();
        });
    };

    $scope.saveAttendance = function() {
        if (!$scope.attendanceItems.length) {
            return;
        }

        $scope.saving = true;
        DBService.postCall({
            type: $scope.filters.type,
            date: $scope.filters.date,
            items: $scope.attendanceItems.map(function(item) {
                return {
                    id: item.id,
                    user_id: item.user_id || null,
                    status: item.status || 'present',
                    remark: item.remark || ''
                };
            })
        }, '/api/admin/attendance/store').then(function(data) {
            alert((data && data.message) ? data.message : 'Failed to save attendance.');
            if (data && data.success) {
                $scope.loadHistory();
            }
            $scope.saving = false;
        }, function() {
            $scope.saving = false;
        });
    };

    $scope.loadHistory = function() {
        DBService.postCall({
            type: $scope.historyFilter.type,
            from_date: $scope.historyFilter.from_date,
            to_date: $scope.historyFilter.to_date
        }, '/api/admin/attendance/list').then(function(data) {
            if (data && data.success) {
                $scope.historyRows = data.rows || [];
            } else {
                $scope.historyRows = [];
            }
        });
    };
});

// *** feeTypesCtrl ***
app.controller('feeTypesCtrl', function($scope , DBService){
    $scope.feeTypes = [];
    $scope.processing = false;
    $scope.formData = {
        id: '',
        name: '',
        active: '1'
    };

    $scope.init = function() {
        DBService.postCall({}, '/api/fee-types/init').then(function(data) {
            if (data && data.success) {
                $scope.feeTypes = data.fee_types || [];
            }
        });
    };

    $scope.openAddModal = function() {
        $scope.formData = { id: '', name: '', active: '1' };
        $('#feeTypeModal').modal('show');
    };

    $scope.openEditModal = function(item) {
        $scope.formData = {
            id: item.id,
            name: item.name,
            active: (item.active == 1 ? '1' : '0')
        };
        $('#feeTypeModal').modal('show');
    };

    $scope.saveFeeType = function() {
        if (!$scope.formData.name) {
            alert('Fee type name is required.');
            return;
        }

        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/fee-types/store').then(function(data) {
            $scope.processing = false;
            if (data && data.success) {
                $('#feeTypeModal').modal('hide');
                $scope.init();
                alert(data.message || 'Saved successfully.');
            } else {
                alert((data && data.message) ? data.message : 'Unable to save fee type.');
            }
        });
    };

    $scope.deleteFeeType = function(item) {
        confirmAction('Delete Fee Type', 'Do you want to delete fee type "' + item.name + '"?', function() {
            DBService.postCall({ id: item.id }, '/api/fee-types/delete').then(function(data) {
                if (data && data.success) {
                    $scope.init();
                    alert(data.message || 'Deleted successfully.');
                } else {
                    alert((data && data.message) ? data.message : 'Unable to delete fee type.');
                }
            });
        });
    };
});

// *** sectionsCtrl ***
app.controller('sectionsCtrl', function($scope , DBService){
    $scope.sections = [];
    $scope.processing = false;
    $scope.formData = {
        id: '',
        name: '',
        active: '1'
    };

    $scope.init = function() {
        DBService.postCall({}, '/api/sections/init').then(function(data) {
            if (data && data.success) {
                $scope.sections = data.sections || [];
            }
        });
    };

    $scope.openAddModal = function() {
        $scope.formData = { id: '', name: '', active: '1' };
        $('#sectionModal').modal('show');
    };

    $scope.openEditModal = function(item) {
        $scope.formData = {
            id: item.id,
            name: item.name,
            active: (item.active == 1 ? '1' : '0')
        };
        $('#sectionModal').modal('show');
    };

    $scope.saveSection = function() {
        if (!$scope.formData.name) {
            alert('Section name is required.');
            return;
        }

        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/sections/store').then(function(data) {
            $scope.processing = false;
            if (data && data.success) {
                $('#sectionModal').modal('hide');
                $scope.init();
                alert(data.message || 'Saved successfully.');
            } else {
                alert((data && data.message) ? data.message : 'Unable to save section.');
            }
        });
    };

    $scope.deleteSection = function(item) {
        confirmAction('Delete Section', 'Do you want to delete section "' + item.name + '"?', function() {
            DBService.postCall({ id: item.id }, '/api/sections/delete').then(function(data) {
                if (data && data.success) {
                    $scope.init();
                    alert(data.message || 'Deleted successfully.');
                } else {
                    alert((data && data.message) ? data.message : 'Unable to delete section.');
                }
            });
        });
    };
});

// *** DBService ***
app.service('DBService', function($http , $rootScope){
    this.getCall = function(route){
        var promise = $http({
            method: 'GET',
            url: base_url + route,
            headers: {
                'apiToken': api_key
            }
        })
        .then(function(response) {
            console.log(response);
            if(response.status == 200){
                if(response.data.success){
                    return response.data;
                } else {
                    return response.data;
                }
            }
        });
        return promise;
    }
    this.postCall = function(data, route){
        var promise = $http({
            method: 'POST',
            url: base_url + route,
            data: data,
            headers: {
                'apiToken': api_key
            }
        })
        .then(function(response) {
            if(response.status == 200){
                if(response.data.success){
                    return response.data;
                } else {
                    return response.data;
                }
            }
        });
        return promise;
    }
});

// *** servicesCtrl ***
app.controller('servicesCtrl', function($scope , DBService){
    $scope.services = [];
    $scope.processing = false;
    $scope.formData = {
        id: '',
        name: '',
        active: '1'
    };

    $scope.init = function() {
        DBService.postCall({}, '/api/services/init').then(function(data) {
            if (data && data.success) {
                $scope.services = data.services || [];
            }
        });
    };

    $scope.openAddModal = function() {
        $scope.formData = { id: '', name: '', active: '1' };
        $('#serviceModal').modal('show');
    };

    $scope.openEditModal = function(item) {
        $scope.formData = {
            id: item.id,
            name: item.name,
            active: (item.active == 1 ? '1' : '0')
        };
        $('#serviceModal').modal('show');
    };

    $scope.saveService = function() {
        if (!$scope.formData.name) {
            alert('Service name is required.');
            return;
        }

        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/services/store').then(function(data) {
            $scope.processing = false;
            if (data && data.success) {
                $('#serviceModal').modal('hide');
                $scope.init();
                alert(data.message || 'Saved successfully.');
            } else {
                alert((data && data.message) ? data.message : 'Unable to save service.');
            }
        });
    };
});

// *** standardsCtrl ***
app.controller('standardsCtrl', function($scope , DBService){
    $scope.standards = [];
    $scope.processing = false;
    $scope.formData = {
        id: '',
        name: '',
        active: '1'
    };

    $scope.init = function() {
        DBService.postCall({}, '/api/standards/init').then(function(data) {
            if (data && data.success) {
                $scope.standards = data.standards || [];
            }
        });
    };

    $scope.openAddModal = function() {
        $scope.formData = { id: '', name: '', active: '1' };
        $('#standardModal').modal('show');
    };

    $scope.openEditModal = function(item) {
        $scope.formData = {
            id: item.id,
            name: item.name,
            active: (item.active == 1 ? '1' : '0')
        };
        $('#standardModal').modal('show');
    };

    $scope.saveStandard = function() {
        if (!$scope.formData.name) {
            alert('Standard name is required.');
            return;
        }

        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/standards/store').then(function(data) {
            $scope.processing = false;
            if (data && data.success) {
                $('#standardModal').modal('hide');
                $scope.init();
                alert(data.message || 'Saved successfully.');
            } else {
                alert((data && data.message) ? data.message : 'Unable to save standard.');
            }
        });
    };

    $scope.deleteStandard = function(item) {
        confirmAction('Delete Standard', 'Do you want to delete standard "' + item.name + '"?', function() {
            DBService.postCall({ id: item.id }, '/api/standards/delete').then(function(data) {
                if (data && data.success) {
                    $scope.init();
                    alert(data.message || 'Deleted successfully.');
                } else {
                    alert((data && data.message) ? data.message : 'Unable to delete standard.');
                }
            });
        });
    };
});

// *** subjectsCtrl ***
app.controller('subjectsCtrl', function($scope , DBService){
    $scope.subjects = [];
    $scope.processing = false;
    $scope.formData = {
        id: '',
        name: '',
        active: '1'
    };

    $scope.init = function() {
        DBService.postCall({}, '/api/subjects/init').then(function(data) {
            if (data && data.success) {
                $scope.subjects = data.subjects || [];
            }
        });
    };

    $scope.openAddModal = function() {
        $scope.formData = { id: '', name: '', active: '1' };
        $('#subjectModal').modal('show');
    };

    $scope.openEditModal = function(item) {
        $scope.formData = {
            id: item.id,
            name: item.name,
            active: (item.active == 1 ? '1' : '0')
        };
        $('#subjectModal').modal('show');
    };

    $scope.saveSubject = function() {
        if (!$scope.formData.name) {
            alert('Subject name is required.');
            return;
        }

        $scope.processing = true;
        DBService.postCall($scope.formData, '/api/subjects/store').then(function(data) {
            $scope.processing = false;
            if (data && data.success) {
                $('#subjectModal').modal('hide');
                $scope.init();
                alert(data.message || 'Saved successfully.');
            } else {
                alert((data && data.message) ? data.message : 'Unable to save subject.');
            }
        });
    };

    $scope.deleteSubject = function(item) {
        confirmAction('Delete Subject', 'Do you want to delete subject "' + item.name + '"?', function() {
            DBService.postCall({ id: item.id }, '/api/subjects/delete').then(function(data) {
                if (data && data.success) {
                    $scope.init();
                    alert(data.message || 'Deleted successfully.');
                } else {
                    alert((data && data.message) ? data.message : 'Unable to delete subject.');
                }
            });
        });
    };
});

// *** studentCtrl ***
app.controller('studentCtrl', function($scope , DBService){
    $scope.loading = false;
    $scope.allStudents = [];
    $scope.students = [];
    $scope.baseUrl = base_url;
    $scope.filters = {
        search: '',
        gender: '',
        status: ''
    };

    $scope.applyFilters = function() {
        var search = ($scope.filters.search || '').toLowerCase().trim();
        var gender = ($scope.filters.gender || '').toLowerCase();
        var status = $scope.filters.status;

        $scope.students = ($scope.allStudents || []).filter(function(item) {
            var fullName = ((item.first_name || item.name || '') + ' ' + (item.last_name || '')).toLowerCase();
            var admissionNo = String(item.admission_no || '').toLowerCase();
            var mobile = String(item.mobile || '').toLowerCase();
            var email = String(item.email || '').toLowerCase();
            var itemGender = String(item.gender || '').toLowerCase();
            var itemStatus = (item.active === 0 || item.active === '0') ? '0' : '1';

            var matchesSearch = !search
                || fullName.indexOf(search) !== -1
                || admissionNo.indexOf(search) !== -1
                || mobile.indexOf(search) !== -1
                || email.indexOf(search) !== -1;
            var matchesGender = !gender || itemGender === gender;
            var matchesStatus = status === '' || itemStatus === status;

            return matchesSearch && matchesGender && matchesStatus;
        });
    };

    $scope.resetFilters = function() {
        $scope.filters = {
            search: '',
            gender: '',
            status: ''
        };
        $scope.applyFilters();
    };

    $scope.init = function() {
        $scope.loading = true;
        DBService.postCall({}, '/api/admin/students/init').then(function(data) {
            if (data && data.success) {
                $scope.allStudents = data.students || [];
            } else {
                $scope.allStudents = [];
                $scope.students = [];
            }
            $scope.applyFilters();
            $scope.loading = false;
        });
    };

    $scope.toggleStatus = function(student, nextStatus) {
        if (!student || !student.enc_id) {
            return;
        }

        var actionLabel = nextStatus === 1 ? 'activate' : 'deactivate';
        confirmAction('Confirm Status Change', 'Are you sure you want to ' + actionLabel + ' this student?', function() {
            DBService.postCall({
                enc_id: student.enc_id,
                active: String(nextStatus)
            }, '/api/admin/students/status').then(function(data) {
                alert((data && data.message) ? data.message : 'Status update failed.');
                if (data && data.success) {
                    $scope.init();
                }
            });
        });
    };
});

app.controller('addStudentCtrl', function($scope , DBService){
    $scope.processing = false;
    $scope.formData = {
        enc_id: '',
        admission_no: '',
        first_name: '',
        last_name: '',
        dob: '',
        gender: '',
        mobile: '',
        email: '',
        address: '',
        aadhar_no: '',
        parent_name: '',
        parent_mobile: '',
        parent_email: '',
        parent_address: '',
        parent_aadhar_no: '',
        document_type: 'Aadhar',
        document_no: '',
        active: '1'
    };

    $scope.init = function(encId) {
        if (!encId) {
            return;
        }

        var decodedId = decodeURIComponent(encId);
        $scope.formData.enc_id = decodedId;
        DBService.postCall({ enc_id: decodedId }, '/api/admin/students/get').then(function(data) {
            if (!(data && data.success && data.student)) {
                return;
            }

            var s = data.student || {};
            var p = data.parent || {};

            $scope.formData.enc_id = data.enc_id || decodedId;
            $scope.formData.admission_no = s.admission_no || '';
            $scope.formData.first_name = s.first_name || s.name || '';
            $scope.formData.last_name = s.last_name || '';
            $scope.formData.dob = s.dob || '';
            $scope.formData.gender = s.gender || '';
            $scope.formData.mobile = s.mobile || '';
            $scope.formData.email = s.email || '';
            $scope.formData.address = s.address || '';
            $scope.formData.aadhar_no = s.aadhar_no || '';
            $scope.formData.active = (s.active !== undefined && s.active !== null) ? String(s.active) : '1';

            $scope.formData.parent_name = p.name || p.parent_name || '';
            $scope.formData.parent_mobile = p.mobile || p.phone || '';
            $scope.formData.parent_email = p.email || '';
            $scope.formData.parent_address = p.address || '';
            $scope.formData.parent_aadhar_no = p.aadhar_no || '';
        });
    };

    $scope.submit = function() {
        $scope.processing = true;

        DBService.postCall($scope.formData, '/api/admin/students/store').then(function(data) {
            alert((data && data.message) ? data.message : 'Unable to save student.');
            if (data && data.success) {
                window.location.href = base_url + '/admin/students';
            }
            $scope.processing = false;
        }, function() {
            $scope.processing = false;
        });
    };
});

app.controller('studentProfileCtrl', function($scope , DBService){
    $scope.baseUrl = base_url;
    $scope.encId = '';
    $scope.student = {};
    $scope.parent = {};

    $scope.init = function(encId) {
        if (!encId) {
            return;
        }

        var decodedId = decodeURIComponent(encId);
        $scope.encId = decodedId;

        DBService.postCall({ enc_id: decodedId }, '/api/admin/students/get').then(function(data) {
            if (!(data && data.success)) {
                return;
            }

            $scope.student = data.student || {};
            $scope.parent = data.parent || {};
            $scope.encId = data.enc_id || decodedId;
        });
    };
});

// *** teacherCtrl ***
app.controller('teacherCtrl', function($scope , DBService){
    $scope.loading = false;
    $scope.allTeachers = [];
    $scope.teachers = [];
    $scope.baseUrl = base_url;
    $scope.filters = {
        search: '',
        gender: '',
        status: ''
    };

    $scope.applyFilters = function() {
        var search = ($scope.filters.search || '').toLowerCase().trim();
        var gender = ($scope.filters.gender || '').toLowerCase();
        var status = $scope.filters.status;

        $scope.teachers = ($scope.allTeachers || []).filter(function(item) {
            var fullName = ((item.first_name || item.name || '') + ' ' + (item.last_name || '')).toLowerCase();
            var mobile = String(item.mobile || '').toLowerCase();
            var email = String(item.email || '').toLowerCase();
            var itemGender = String(item.gender || '').toLowerCase();
            var itemStatus = (item.active === 0 || item.active === '0') ? '0' : '1';

            var matchesSearch = !search
                || fullName.indexOf(search) !== -1
                || mobile.indexOf(search) !== -1
                || email.indexOf(search) !== -1;
            var matchesGender = !gender || itemGender === gender;
            var matchesStatus = status === '' || itemStatus === status;

            return matchesSearch && matchesGender && matchesStatus;
        });
    };

    $scope.resetFilters = function() {
        $scope.filters = {
            search: '',
            gender: '',
            status: ''
        };
        $scope.applyFilters();
    };

    $scope.init = function() {
        $scope.loading = true;
        DBService.postCall({}, '/api/admin/teachers/init').then(function(data) {
            if (data && data.success) {
                $scope.allTeachers = data.teachers || [];
            } else {
                $scope.allTeachers = [];
                $scope.teachers = [];
            }
            $scope.applyFilters();
            $scope.loading = false;
        });
    };
});

app.controller('addTeacherCtrl', function($scope , DBService){
    $scope.processing = false;
    $scope.formData = {
        enc_id: '',
        first_name: '',
        last_name: '',
        dob: '',
        gender: '',
        mobile: '',
        email: '',
        address: '',
        aadhar_no: '',
        document_type: 'Aadhar',
        document_no: '',
        active: '1'
    };

    $scope.init = function(encId) {
        if (!encId) {
            return;
        }

        var decodedId = decodeURIComponent(encId);
        $scope.formData.enc_id = decodedId;

        DBService.postCall({ enc_id: decodedId }, '/api/admin/teachers/get').then(function(data) {
            if (!(data && data.success && data.teacher)) {
                return;
            }

            var t = data.teacher || {};
            $scope.formData.enc_id = data.enc_id || decodedId;
            $scope.formData.first_name = t.first_name || t.name || '';
            $scope.formData.last_name = t.last_name || '';
            $scope.formData.dob = t.dob || '';
            $scope.formData.gender = t.gender || '';
            $scope.formData.mobile = t.mobile || '';
            $scope.formData.email = t.email || '';
            $scope.formData.address = t.address || '';
            $scope.formData.aadhar_no = t.aadhar_no || '';
            $scope.formData.active = (t.active !== undefined && t.active !== null) ? String(t.active) : '1';
        });
    };

    $scope.submit = function() {
        $scope.processing = true;

        DBService.postCall($scope.formData, '/api/admin/teachers/store').then(function(data) {
            alert((data && data.message) ? data.message : 'Unable to save teacher.');
            if (data && data.success) {
                window.location.href = base_url + '/admin/teachers';
            }
            $scope.processing = false;
        }, function() {
            $scope.processing = false;
        });
    };
});

// *** adminDashboardCtrl ***
app.controller('adminDashboardCtrl', function($scope , DBService){
    $scope.cards = [];
    $scope.attendance = [];
    var cardStyleMap = {
        students: { gradientClass: 'gradient-bg-end-1', iconColorClass: 'bg-warning-600' },
        teachers: { gradientClass: 'gradient-bg-end-2', iconColorClass: 'bg-blue-600' },
        sections: { gradientClass: 'gradient-bg-end-3', iconColorClass: 'bg-purple-600' },
        subjects: { gradientClass: 'gradient-bg-end-4', iconColorClass: 'bg-primary-600' },
        services: { gradientClass: 'gradient-bg-end-5', iconColorClass: 'bg-success-600' },
        fee_types: { gradientClass: 'gradient-bg-end-6', iconColorClass: 'bg-cyan-600' }
    };

    $scope.init = function() {
        DBService.postCall({}, '/api/admin/dashboard/init').then(function(data) {
            if (!(data && data.success && data.stats)) {
                $scope.cards = [];
                $scope.attendance = [];
                return;
            }

            var stats = data.stats;

            $scope.cards = [
                {
                    key: 'students',
                    label: 'Total Students',
                    icon: 'dashboard-icon1.png',
                    gradientClass: cardStyleMap.students.gradientClass,
                    iconBgClass: cardStyleMap.students.iconColorClass,
                    mainValue: parseInt((stats.students && stats.students.total) || 0, 10),
                    active: parseInt((stats.students && stats.students.active) || 0, 10),
                    inactive: parseInt((stats.students && stats.students.inactive) || 0, 10)
                },
                {
                    key: 'teachers',
                    label: 'Total Teachers',
                    icon: 'dashboard-icon2.png',
                    gradientClass: cardStyleMap.teachers.gradientClass,
                    iconBgClass: cardStyleMap.teachers.iconColorClass,
                    mainValue: parseInt((stats.teachers && stats.teachers.total) || 0, 10),
                    active: parseInt((stats.teachers && stats.teachers.active) || 0, 10),
                    inactive: parseInt((stats.teachers && stats.teachers.inactive) || 0, 10)
                },
                {
                    key: 'sections',
                    label: 'Total Sections',
                    icon: 'dashboard-icon3.png',
                    gradientClass: cardStyleMap.sections.gradientClass,
                    iconBgClass: cardStyleMap.sections.iconColorClass,
                    mainValue: parseInt((stats.sections && stats.sections.total) || 0, 10),
                    active: parseInt((stats.sections && stats.sections.active) || 0, 10),
                    inactive: parseInt((stats.sections && stats.sections.inactive) || 0, 10)
                },
                {
                    key: 'subjects',
                    label: 'Total Subjects',
                    icon: 'dashboard-icon4.png',
                    gradientClass: cardStyleMap.subjects.gradientClass,
                    iconBgClass: cardStyleMap.subjects.iconColorClass,
                    mainValue: parseInt((stats.subjects && stats.subjects.total) || 0, 10),
                    active: parseInt((stats.subjects && stats.subjects.active) || 0, 10),
                    inactive: parseInt((stats.subjects && stats.subjects.inactive) || 0, 10)
                },
                {
                    key: 'services',
                    label: 'Total Services',
                    icon: 'dashboard-icon5.png',
                    gradientClass: cardStyleMap.services.gradientClass,
                    iconBgClass: cardStyleMap.services.iconColorClass,
                    mainValue: parseInt((stats.services && stats.services.total) || 0, 10),
                    active: parseInt((stats.services && stats.services.active) || 0, 10),
                    inactive: parseInt((stats.services && stats.services.inactive) || 0, 10)
                },
                {
                    key: 'fee_types',
                    label: 'Total Fee Types',
                    icon: 'dashboard-icon6.png',
                    gradientClass: cardStyleMap.fee_types.gradientClass,
                    iconBgClass: cardStyleMap.fee_types.iconColorClass,
                    mainValue: parseInt((stats.feeTypes && stats.feeTypes.total) || 0, 10),
                    active: parseInt((stats.feeTypes && stats.feeTypes.active) || 0, 10),
                    inactive: parseInt((stats.feeTypes && stats.feeTypes.inactive) || 0, 10)
                }
            ];

            $scope.attendance = Array.isArray(data.attendance) ? data.attendance.map(function(item) {
                item.badgeClass = item.badge_class || 'text-bg-secondary';
                item.barClass = item.bar_class || 'bg-neutral-300';
                return item;
            }) : [];
        });
    };
});

// *** teacherDashboardCtrl ***
app.controller('teacherDashboardCtrl', function($scope , DBService){
    $scope.loading = true;
    $scope.today = '';
    $scope.teacherProfile = {};
    $scope.cards = [];
    $scope.studentAttendanceToday = [];
    $scope.teacherAttendanceToday = [];
    $scope.myAttendance = [];
    $scope.myAttendanceTotal = 0;
    $scope.recentStudentAttendance = [];

    $scope.init = function() {
        DBService.postCall({}, '/api/teachers/dashboard/init').then(function(data) {
            $scope.loading = false;
            if (!(data && data.success)) {
                return;
            }

            $scope.today = data.today || '';
            $scope.teacherProfile = data.teacherProfile || {};
            $scope.cards = data.cards || [];
            $scope.studentAttendanceToday = data.studentAttendanceToday || [];
            $scope.teacherAttendanceToday = data.teacherAttendanceToday || [];
            $scope.myAttendance = data.myAttendance || [];
            $scope.myAttendanceTotal = parseInt(data.myAttendanceTotal || 0, 10);
            $scope.recentStudentAttendance = data.recentStudentAttendance || [];
        }, function() {
            $scope.loading = false;
        });
    };
});

// *** guardianDashboardCtrl ***
app.controller('guardianDashboardCtrl', function($scope , DBService){
    $scope.loading = true;
    $scope.today = '';
    $scope.guardian = {};
    $scope.children = [];

    $scope.init = function() {
        DBService.postCall({}, '/api/gurdian/dashboard/init').then(function(data) {
            $scope.loading = false;
            if (!(data && data.success)) {
                $scope.guardian = {};
                $scope.children = [];
                return;
            }

            $scope.today = data.today || '';
            $scope.guardian = data.guardian || {};
            $scope.children = data.children || [];
        }, function() {
            $scope.loading = false;
            $scope.guardian = {};
            $scope.children = [];
        });
    };
});

// *** chatCtrl ***
app.controller('chatCtrl', function($scope , DBService, $timeout){
    $scope.users = [];
    $scope.messages = [];
    $scope.selectedUser = null;
    $scope.searchUser = '';
    $scope.sending = false;
    $scope.loadingThread = false;
    $scope.draft = { message: '' };

    $scope.init = function() {
        DBService.postCall({}, '/api/chat/init').then(function(data) {
            if (!(data && data.success)) {
                $scope.users = [];
                $scope.messages = [];
                $scope.selectedUser = null;
                return;
            }

            $scope.users = data.users || [];
            var selectedUserId = parseInt(data.selected_user_id || 0, 10);
            if (selectedUserId > 0) {
                for (var i = 0; i < $scope.users.length; i++) {
                    if (parseInt($scope.users[i].id || 0, 10) === selectedUserId) {
                        $scope.selectedUser = $scope.users[i];
                        break;
                    }
                }
            }
            $scope.messages = data.messages || [];
        });
    };

    $scope.selectUser = function(user) {
        if (!user || !user.id) {
            return;
        }
        $scope.selectedUser = user;
        $scope.fetchThread();
    };

    $scope.fetchThread = function() {
        if (!$scope.selectedUser || !$scope.selectedUser.id) {
            $scope.messages = [];
            return;
        }

        $scope.loadingThread = true;
        DBService.postCall({
            user_id: $scope.selectedUser.id
        }, '/api/chat/thread').then(function(data) {
            $scope.loadingThread = false;
            if (data && data.success) {
                $scope.messages = data.messages || [];
                $scope.scrollBottom();
            } else {
                $scope.messages = [];
            }
        }, function() {
            $scope.loadingThread = false;
            $scope.messages = [];
        });
    };

    $scope.sendMessage = function() {
        if (!$scope.selectedUser || !$scope.selectedUser.id) {
            return;
        }

        var message = ($scope.draft.message || '').trim();
        if (!message) {
            return;
        }

        $scope.sending = true;
        DBService.postCall({
            user_id: $scope.selectedUser.id,
            message: message
        }, '/api/chat/send').then(function(data) {
            $scope.sending = false;
            if (data && data.success) {
                $scope.draft.message = '';
                $scope.messages = data.messages || [];
                $scope.scrollBottom();
            } else {
                alert((data && data.message) ? data.message : 'Unable to send message.');
            }
        }, function() {
            $scope.sending = false;
            alert('Unable to send message.');
        });
    };

    $scope.handleEnter = function($event) {
        if ($event.keyCode === 13 && !$event.shiftKey) {
            $event.preventDefault();
            $scope.sendMessage();
        }
    };

    $scope.scrollBottom = function() {
        $timeout(function() {
            var box = document.getElementById('chatThreadBox');
            if (box) {
                box.scrollTop = box.scrollHeight;
            }
        }, 80);
    };
});

// *** examMarksCtrl ***
app.controller('examMarksCtrl', function($scope , DBService){
    function todayDate() {
        var d = new Date();
        var m = String(d.getMonth() + 1).padStart(2, '0');
        var day = String(d.getDate()).padStart(2, '0');
        return d.getFullYear() + '-' + m + '-' + day;
    }

    $scope.students = [];
    $scope.subjects = [];
    $scope.rows = [];
    $scope.saving = false;
    $scope.filters = {
        exam_name: '',
        student_id: '',
        subject_id: ''
    };
    $scope.formData = {};

    $scope.resetForm = function() {
        $scope.formData = {
            id: '',
            exam_name: '',
            exam_date: todayDate(),
            student_id: '',
            subject_id: '',
            total_marks: 100,
            obtained_marks: '',
            remark: ''
        };
    };

    $scope.init = function() {
        $scope.resetForm();
        DBService.postCall({}, '/api/teachers/exam-marks/init').then(function(data) {
            if (!(data && data.success)) {
                $scope.students = [];
                $scope.subjects = [];
                $scope.rows = [];
                return;
            }

            $scope.students = data.students || [];
            $scope.subjects = data.subjects || [];
            $scope.rows = data.rows || [];
        });
    };

    $scope.loadRows = function() {
        DBService.postCall({
            exam_name: $scope.filters.exam_name || '',
            student_id: $scope.filters.student_id || null,
            subject_id: $scope.filters.subject_id || null
        }, '/api/teachers/exam-marks/list').then(function(data) {
            if (data && data.success) {
                $scope.rows = data.rows || [];
            } else {
                $scope.rows = [];
            }
        });
    };

    $scope.resetFilters = function() {
        $scope.filters = { exam_name: '', student_id: '', subject_id: '' };
        $scope.loadRows();
    };

    $scope.editRow = function(row) {
        $scope.formData = {
            id: row.id,
            exam_name: row.exam_name,
            exam_date: row.exam_date,
            student_id: String(row.student_id || ''),
            subject_id: row.subject_id ? String(row.subject_id) : '',
            total_marks: row.total_marks,
            obtained_marks: row.obtained_marks,
            remark: row.remark || ''
        };
        window.scrollTo({ top: 0, behavior: 'smooth' });
    };

    $scope.saveMark = function() {
        if (!$scope.formData.student_id || !$scope.formData.exam_name || !$scope.formData.exam_date) {
            alert('Please fill required fields.');
            return;
        }

        if (parseFloat($scope.formData.obtained_marks || 0) > parseFloat($scope.formData.total_marks || 0)) {
            alert('Obtained marks cannot be greater than total marks.');
            return;
        }

        $scope.saving = true;
        DBService.postCall({
            id: $scope.formData.id || null,
            exam_name: $scope.formData.exam_name,
            exam_date: $scope.formData.exam_date,
            student_id: parseInt($scope.formData.student_id, 10),
            subject_id: $scope.formData.subject_id ? parseInt($scope.formData.subject_id, 10) : null,
            total_marks: parseFloat($scope.formData.total_marks || 0),
            obtained_marks: parseFloat($scope.formData.obtained_marks || 0),
            remark: $scope.formData.remark || ''
        }, '/api/teachers/exam-marks/store').then(function(data) {
            $scope.saving = false;
            if (data && data.success) {
                alert(data.message || 'Saved successfully.');
                $scope.rows = data.rows || [];
                $scope.resetForm();
            } else {
                alert((data && data.message) ? data.message : 'Unable to save marks.');
            }
        }, function() {
            $scope.saving = false;
            alert('Unable to save marks.');
        });
    };
});

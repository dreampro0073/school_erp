app.controller('teacherCtrl', function($scope, DBService) {
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

app.controller('addTeacherCtrl', function($scope, DBService) {
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

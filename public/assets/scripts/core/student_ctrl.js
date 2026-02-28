app.controller('studentCtrl', function($scope, DBService) {
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
        if (!window.confirm('Are you sure you want to ' + actionLabel + ' this student?')) {
            return;
        }

        DBService.postCall({
            enc_id: student.enc_id,
            active: String(nextStatus)
        }, '/api/admin/students/status').then(function(data) {
            alert((data && data.message) ? data.message : 'Status update failed.');
            if (data && data.success) {
                $scope.init();
            }
        });
    };
});

app.controller('addStudentCtrl', function($scope, DBService) {
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

app.controller('studentProfileCtrl', function($scope, DBService) {
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

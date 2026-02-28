app.controller('attendanceCtrl', function($scope, DBService) {
    var now = new Date();
    var month = String(now.getMonth() + 1).padStart(2, '0');
    var day = String(now.getDate()).padStart(2, '0');
    var today = now.getFullYear() + '-' + month + '-' + day;

    $scope.saving = false;
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
                $scope.allItems = data.items || [];
            } else {
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

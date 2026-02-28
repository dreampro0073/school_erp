app.controller('adminDashboardCtrl', function($scope, DBService) {
    $scope.cards = [];
    $scope.attendance = [];
    var attendanceClassMap = {
        present: 'text-bg-success',
        absent: 'text-bg-danger',
        late: 'text-bg-warning',
        half_day: 'text-bg-info'
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
                    iconBgClass: 'bg-primary-subtle',
                    mainValue: parseInt((stats.students && stats.students.total) || 0, 10),
                    active: parseInt((stats.students && stats.students.active) || 0, 10),
                    inactive: parseInt((stats.students && stats.students.inactive) || 0, 10)
                },
                {
                    key: 'teachers',
                    label: 'Total Teachers',
                    icon: 'dashboard-icon2.png',
                    iconBgClass: 'bg-success-subtle',
                    mainValue: parseInt((stats.teachers && stats.teachers.total) || 0, 10),
                    active: parseInt((stats.teachers && stats.teachers.active) || 0, 10),
                    inactive: parseInt((stats.teachers && stats.teachers.inactive) || 0, 10)
                },
                {
                    key: 'sections',
                    label: 'Total Sections',
                    icon: 'dashboard-icon3.png',
                    iconBgClass: 'bg-warning-subtle',
                    mainValue: parseInt((stats.sections && stats.sections.total) || 0, 10),
                    active: parseInt((stats.sections && stats.sections.active) || 0, 10),
                    inactive: parseInt((stats.sections && stats.sections.inactive) || 0, 10)
                },
                {
                    key: 'subjects',
                    label: 'Total Subjects',
                    icon: 'dashboard-icon4.png',
                    iconBgClass: 'bg-info-subtle',
                    mainValue: parseInt((stats.subjects && stats.subjects.total) || 0, 10),
                    active: parseInt((stats.subjects && stats.subjects.active) || 0, 10),
                    inactive: parseInt((stats.subjects && stats.subjects.inactive) || 0, 10)
                },
                {
                    key: 'services',
                    label: 'Total Services',
                    icon: 'dashboard-icon5.png',
                    iconBgClass: 'bg-danger-subtle',
                    mainValue: parseInt((stats.services && stats.services.total) || 0, 10),
                    active: parseInt((stats.services && stats.services.active) || 0, 10),
                    inactive: parseInt((stats.services && stats.services.inactive) || 0, 10)
                },
                {
                    key: 'fee_types',
                    label: 'Total Fee Types',
                    icon: 'dashboard-icon6.png',
                    iconBgClass: 'bg-secondary-subtle',
                    mainValue: parseInt((stats.feeTypes && stats.feeTypes.total) || 0, 10),
                    active: parseInt((stats.feeTypes && stats.feeTypes.active) || 0, 10),
                    inactive: parseInt((stats.feeTypes && stats.feeTypes.inactive) || 0, 10)
                }
            ];

            $scope.attendance = Array.isArray(data.attendance) ? data.attendance.map(function(item) {
                item.badgeClass = attendanceClassMap[item.key] || 'text-bg-secondary';
                return item;
            }) : [];
        });
    };
});

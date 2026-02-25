app.controller('dashboardCtrl', function($scope, DBService) {
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

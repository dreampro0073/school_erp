app.controller('servicesCtrl', function($scope, DBService) {
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

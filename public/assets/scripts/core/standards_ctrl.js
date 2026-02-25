app.controller('standardsCtrl', function($scope, DBService) {
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
        if (!window.confirm('Delete standard "' + item.name + '"?')) {
            return;
        }

        DBService.postCall({ id: item.id }, '/api/standards/delete').then(function(data) {
            if (data && data.success) {
                $scope.init();
                alert(data.message || 'Deleted successfully.');
            } else {
                alert((data && data.message) ? data.message : 'Unable to delete standard.');
            }
        });
    };
});

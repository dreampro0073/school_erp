app.controller('feeTypesCtrl', function($scope, DBService) {
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
        if (!window.confirm('Delete fee type "' + item.name + '"?')) {
            return;
        }

        DBService.postCall({ id: item.id }, '/api/fee-types/delete').then(function(data) {
            if (data && data.success) {
                $scope.init();
                alert(data.message || 'Deleted successfully.');
            } else {
                alert((data && data.message) ? data.message : 'Unable to delete fee type.');
            }
        });
    };
});


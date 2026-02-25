app.controller('sectionsCtrl', function($scope, DBService) {
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
        if (!window.confirm('Delete section "' + item.name + '"?')) {
            return;
        }

        DBService.postCall({ id: item.id }, '/api/sections/delete').then(function(data) {
            if (data && data.success) {
                $scope.init();
                alert(data.message || 'Deleted successfully.');
            } else {
                alert((data && data.message) ? data.message : 'Unable to delete section.');
            }
        });
    };
});

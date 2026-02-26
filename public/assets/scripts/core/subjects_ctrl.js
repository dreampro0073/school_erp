app.controller('subjectsCtrl', function($scope, DBService) {
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
        if (!window.confirm('Delete subject "' + item.name + '"?')) {
            return;
        }

        DBService.postCall({ id: item.id }, '/api/subjects/delete').then(function(data) {
            if (data && data.success) {
                $scope.init();
                alert(data.message || 'Deleted successfully.');
            } else {
                alert((data && data.message) ? data.message : 'Unable to delete subject.');
            }
        });
    };
});


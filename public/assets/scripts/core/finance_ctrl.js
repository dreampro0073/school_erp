function setupMasterCtrl(app, ctrlName, initRoute, storeRoute) {
    app.controller(ctrlName, function($scope, DBService) {
        $scope.rows = [];
        $scope.formData = { id: '', name: '', active: '1' };

        $scope.init = function() {
            DBService.postCall({}, initRoute).then(function(data) {
                if (data && data.success) {
                    var key = Object.keys(data).filter(function(k){ return k !== 'success' && k !== 'message'; })[0];
                    $scope.rows = data[key] || [];
                }
            });
        };

        $scope.edit = function(item) {
            $scope.formData = {
                id: item.id,
                name: item.name,
                active: String(item.active === undefined ? 1 : item.active)
            };
        };

        $scope.save = function() {
            DBService.postCall($scope.formData, storeRoute).then(function(data) {
                alert((data && data.message) ? data.message : 'Unable to save');
                if (data && data.success) {
                    $scope.formData = { id: '', name: '', active: '1' };
                    $scope.init();
                }
            });
        };
    });
}

function setupEntryCtrl(app, ctrlName, initRoute, storeRoute) {
    app.controller(ctrlName, function($scope, DBService) {
        $scope.rows = [];
        $scope.formData = { id: '', master_id: '', date: '', amount: '', remark: '', active: '1' };

        $scope.init = function() {
            DBService.postCall({}, initRoute).then(function(data) {
                if (data && data.success) {
                    $scope.rows = data.entries || [];
                }
            });
        };

        $scope.edit = function(item) {
            $scope.formData = {
                id: item.id,
                master_id: item.income_id || item.incomes_id || item.expense_id || item.expenses_id || '',
                date: item.date || '',
                amount: item.amount || '',
                remark: item.remark || '',
                active: String(item.active === undefined ? 1 : item.active)
            };
        };

        $scope.save = function() {
            DBService.postCall($scope.formData, storeRoute).then(function(data) {
                alert((data && data.message) ? data.message : 'Unable to save');
                if (data && data.success) {
                    $scope.formData = { id: '', master_id: '', date: '', amount: '', remark: '', active: '1' };
                    $scope.init();
                }
            });
        };
    });
}

setupMasterCtrl(app, 'incomesCtrl', '/api/admin/incomes/init', '/api/admin/incomes/store');
setupMasterCtrl(app, 'expensesCtrl', '/api/admin/expenses/init', '/api/admin/expenses/store');
setupEntryCtrl(app, 'incomeEntriesCtrl', '/api/admin/income-entries/init', '/api/admin/income-entries/store');
setupEntryCtrl(app, 'expenseEntriesCtrl', '/api/admin/expense-entries/init', '/api/admin/expense-entries/store');

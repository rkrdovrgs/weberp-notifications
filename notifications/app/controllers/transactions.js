angular.module('app').controller('transactions', function ($http, $scope) {
    var vm = this;

    vm.title = 'Transacciones bancarias';

    vm.transactions = [];

    vm.filters = {
        fromDate: null,
        toDate: null,
        transType: null,
        page: 1,
        rows: 25
    };

    vm.refresh = function refresh() {
        console.log('Last refreshed:', new Date());
        //get transactions list
        $http.get('/weberp/notifications/api/transactions.php', { params: vm.filters })
            .success(function (data) {
                angular.copy(data, vm.transactions);
            });
    }

    vm.refresh();

    $scope.$on('newNotification', vm.refresh);

});


angular.module('app').controller('transactions', function ($http, $scope) {
    var vm = this;

    vm.title = 'Transacciones bancarias';

    vm.transactions = [];

    refresh();

    function refresh() {
        //get transactions list
        $http.get('/weberp/notifications/api/transactions.php',
            {
                 params: {
                     rows: 25,
                     page: 1
                 }
            })
            .success(function (data) {
                angular.copy(data, vm.transactions);
            });
    }

    $scope.$on('newNotification', refresh);
});


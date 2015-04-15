angular.module('app').controller('transactions', function ($http) {
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

    $(document).on('newNotification', refresh);
});


angular.module('app').controller('transactions', function ($http, $scope) {
    var vm = this;

    vm.title = 'Transacciones bancarias';

    vm.transactions = [];

    $scope.filters = {
        fromDate: null,
        toDate: null,
        transType: null,
        page: 1,
        rows: 25
    };
    var filtersTimeout = null;
    $scope.$watch('filters', function (newVal, oldVal) {
        console.log($scope.filters.page);
        if (newVal !== oldVal) {
            if (newVal.page > 1 && newVal.page === oldVal.page) return $scope.filters.page = 1;
            if (filtersTimeout) clearTimeout(filtersTimeout);
            filtersTimeout = setTimeout(getTransactions, 500);
        }

    }, true);

    refresh();

    function getTransactions() {

        $http.get('/weberp/notifications/api/transactions.php', { params: $scope.filters })
            .success(function (data) {
                angular.copy(data, vm.transactions);
            });
    }


    function refresh() {
        //get transactions list
        getTransactions();
    }

    $scope.$on('newNotification', refresh);
});


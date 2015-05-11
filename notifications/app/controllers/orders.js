angular.module('app').controller('orders', function ($http, $scope) {
    var vm = this;

    vm.title = 'Orders history';

    vm.orders = [];

    vm.filters = {
        client: null,
        branch: null,
        dateFrom: null,
        dateTo: null,
        pendingOnly: null,
        page: 1,
        rows: 25
    };

    vm.refresh = function refresh() {
        console.log('Last refreshed:', new Date());

        //get order list
        $http.get('/weberp/notifications/api/orders.php', { params: vm.filters })
            .success(function (data) {
                angular.copy(data, vm.orders);
            });
    }

    vm.refresh();

    $scope.$on('newNotification', vm.refresh);

});

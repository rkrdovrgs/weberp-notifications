angular.module('app').controller('stock', function ($http, $scope) {
    var vm = this;

    vm.title = 'Stock';

    vm.stock = [];

    vm.filters = {
        description: null,
        category: null,
        location: null,
        quantityFrom: null,
        quantityTo: null,
        page: 1,
        rows: 25
    };

    vm.refresh = function refresh() {
        console.log('Last refreshed:', new Date());

        //get transactions list
        $http.get('/weberp/notifications/api/stock.php', { params: vm.filters })
            .success(function (data) {
                angular.copy(data, vm.stock);
            });
    }

    vm.refresh();

    $scope.$on('newNotification', vm.refresh);

});
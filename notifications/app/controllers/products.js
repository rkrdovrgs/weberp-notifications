angular.module('app').controller('products', function ($http, $scope) {
    var vm = this;

    vm.title = 'Productos poco vendidos';

    vm.products = [];

    vm.filters = {
        description: null,
        category: null,
        quantityFrom: null,
        quantityTo: null,
        page: 1,
        rows: 25
    };

    vm.refresh = function refresh() {
        console.log('Last refreshed:', new Date());

        //get transactions list
        $http.get('/weberp/notifications/api/products.php', { params: vm.filters })
            .success(function (data) {
                angular.copy(data, vm.products);
            });
    }

    vm.refresh();

    $scope.$on('newNotification', vm.refresh);

});
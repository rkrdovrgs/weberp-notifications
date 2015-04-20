angular.module('app').controller('credits', function ($http, $scope) {
    var vm = this;

    vm.title = 'Créditos';

    vm.credits = [];

    vm.filters = {
        client: null,
        dateFrom: null,
        dateTo: null,
        page: 1,
        rows: 25
    };

    vm.refresh = function refresh() {
        console.log('Last refreshed:', new Date());

        //get order list
        $http.get('/weberp/notifications/api/credits.php', { params: vm.filters })
            .success(function (data) {
                angular.copy(data, vm.credits);
            });
    }

    vm.refresh();

    $scope.$on('newNotification', vm.refresh);

});



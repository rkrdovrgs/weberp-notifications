angular.module('app').controller('navigation', function ($http, $scope) {
    var vm = this;

    vm.notificationTypeCounter = {
        stock: 0,
        credits: 0,
        products: 0
    };

    refresh();

    function refresh() {
        //reset notifications counter


        //get notificationstype counter
        $http.get('/weberp/notifications/api/notificationTypeCounter.php')
            .success(function (data) {
                angular.extend(vm.notificationTypeCounter, data);
            });
    }

    $scope.$on('newNotification', refresh);
});
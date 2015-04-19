angular.module('app').controller('dashboard', function ($http, $scope) {
    var vm = this;

    vm.title = 'Dashboard';
    vm.notifications = [];
    vm.transactions = [];
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

        //get transactions list
        $http.get('/weberp/notifications/api/transactions.php', { params: { rows: 5 } })
            .success(function (data) {
                angular.copy(data, vm.transactions);
            });

        //get notifications list
        $http.get('/weberp/notifications/api/notifications.php')
            .success(function (data) {
                $http.post('/weberp/notifications/api/resetNotifications.php');
                $('#notifications-link i').hide();
                angular.forEach(data, function (ntf) {
                    ntf.dateAndTime = new Date(ntf.dateAndTime);
                    ntf.isNew = ntf.isNew === "1" ? true : false;
                });
                angular.copy(data, vm.notifications);

            });
    }

    $scope.$on('newNotification', refresh);
});


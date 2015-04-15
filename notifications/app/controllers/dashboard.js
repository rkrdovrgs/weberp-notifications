angular.module('app').controller('dashboard', function ($http) {
    var vm = this;

    vm.title = 'Dashboard';
    vm.notifications = [];
    vm.transactions = [];
    vm.notificationTypeCounter = {};

    refresh();

    function refresh() {
        //reset notifications counter

        //get notificationstype counter
        //$http.get('/weberp/notifications/api/notificationTypeCounter.php')
        //    .success(function (data) {
        //        vm.notificationTypeCounter = data;
        //    });

        //get transactions list
        $http.get('/weberp/notifications/api/transactions.php', { params: { rows: 5 } })
            .success(function (data) {
                angular.copy(data, vm.transactions);
            });

        //get notifications list
        $http.get('/weberp/notifications/api/notifications.php')
            .success(function (data) {
                angular.forEach(data, function (ntf) {
                    ntf.dateAndTime = new Date(ntf.dateAndTime);
                });
                angular.copy(data, vm.notifications);
            });
    }

    $(document).on('newNotification', refresh);
});


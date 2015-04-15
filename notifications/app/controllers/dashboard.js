angular.module('app').controller('dashboard', function ($http) {
    var vm = this;
    
    vm.title = 'Dashboard';
    vm.notifications = [];
    
    refreshNotifications();

    function refreshNotifications() {
        $http.get('/weberp/notifications/api/notifications.php')
            .success(function (data) {
                angular.copy(data, vm.notifications);
            });
    }    

    $(document).on('newNotification', refreshNotifications);
});


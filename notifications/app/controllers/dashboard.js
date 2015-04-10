(function () {
    'use strict';
    var controllerId = 'dashboard';
    angular.module('app').controller(controllerId, [dashboard]);

    function dashboard() {
        var vm = this;
        vm.news = {
            title: 'Hot Towel Angular',
            description: 'Hot Towel Angular is a SPA template for Angular developers.'
        };
        vm.messageCount = 0;
        vm.people = [];
        vm.title = 'Dashboard';

       
    }
})();
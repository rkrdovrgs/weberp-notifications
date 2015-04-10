(function () {
    'use strict';

    angular.module('app', [
        // Angular modules 
        'ngAnimate',
        'ngRoute'

        // Custom modules 

        // 3rd Party Modules
        
    ]);

    angular.module('app').run(["$rootScope", function($rootScope) {
        $rootScope.isViewLoading = false;
        $rootScope.$on('$routeChangeStart', function () {
            $rootScope.isViewLoading = true;
        });
        $rootScope.$on('$routeChangeSuccess', function () {
            $rootScope.isViewLoading = false;
        });
    }]);
})();
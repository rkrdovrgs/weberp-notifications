(function () {
    'use strict';

    angular
        .module('app')
        .directive('pagination', pagination);

    pagination.$inject = ['$window'];

    function pagination($window) {
        // Usage:
        //     <pagination></pagination>
        // Creates:
        // 
        var directive = {
            link: link,
            restrict: 'E',
            scope: {
                filters: '=ngModel',
                collection: '=ngCollection',
                onPageChange: '&ngChange'
            },
            templateUrl: '/weberp/notifications/app/templates/pagination.html'
        };
        return directive;

        function link(scope, element, attrs) {

            var filtersTimeout = null;
            scope.$watch('filters', function (newVal, oldVal) {
                //console.log(scope.filters.page);
                if (newVal !== oldVal) {
                    if (newVal.page > 1 && newVal.page === oldVal.page) return scope.filters.page = 1;
                    if (filtersTimeout) clearTimeout(filtersTimeout);
                    filtersTimeout = setTimeout(scope.onPageChange, 300);
                }

            }, true);
        }
    }

})();
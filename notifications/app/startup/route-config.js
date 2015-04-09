angular.module('app').config([
        "$routeProvider",


        function ($routeProvider) {
            //$routeProvider.setBaseTemplateUrl('../app/views/');
            $routeProvider
                .when("/", {
                    templateUrl: "app/views/index.html"
                })
                .otherwise({ redirectTo: "/" });
        }
]);
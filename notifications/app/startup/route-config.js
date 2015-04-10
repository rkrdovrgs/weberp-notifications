angular.module('app').config([
        "$routeProvider",


        function ($routeProvider) {
            //$routeProvider.setBaseTemplateUrl('../app/views/');
            $routeProvider
                .when("/", {
                    templateUrl: "app/views/dashboard.html"
                })
                .when("/stock", {
                    templateUrl: "app/views/stock.html"
                })
                .when("/products", {
                    templateUrl: "app/views/products.html"
                })
                .when("/credits", {
                    templateUrl: "app/views/credits.html"
                })
                .when("/transactions", {
                    templateUrl: "app/views/transactions.html"
                })
                .when("/orders", {
                    templateUrl: "app/views/orders.html"
                })
                .otherwise({ redirectTo: "/" });
        }
]);
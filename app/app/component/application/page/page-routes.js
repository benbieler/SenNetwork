'use strict';

angular.module('sen.app.page.routes', [])
    .config(function ($routeProvider) {

        $routeProvider
            .when('/not-found', {
                templateUrl: '/app/component/application/page/view/not-found.html'
            })
            .otherwise({
                redirectTo: '/not-found'
            });

    });

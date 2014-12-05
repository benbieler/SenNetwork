'use strict';

angular.module('sen.app.user.routes', [])
    .config(['$routeProvider', function ($routeProvider) {

        $routeProvider
            .when('/landing', {
                templateUrl: '/app/component/application/user/token/landing.html',
                controller: 'login'
            })
            .when('/create-account', {
                templateUrl: '/app/component/application/user/account/create-account.html',
                controller: 'create-account'
            })
            .when('/registration/success', {
                templateUrl: '/app/component/application/user/account/registration-success.html'
            });

    }]);

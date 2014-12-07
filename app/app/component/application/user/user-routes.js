/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

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

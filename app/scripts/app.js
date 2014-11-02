'use strict';

if (typeof initial === 'undefined') {
    throw new Error('Initial config object (initial) cannot be found!');
}

angular.module(
    'sententiaregum',
    ['ngRoute', 'ngCookies', 'sen.registration', 'sen.login', 'sen.microblog', 'sen.page']
)
    .config(['$routeProvider', '$httpProvider', '$locationProvider',
        function ($routeProvider, $httpProvider, $locationProvider) {

        $locationProvider.hashPrefix('!');

        $routeProvider
            .when('/', {
                templateUrl: '/partials/pages/microblog/dashboard.html',
                controller: 'dashboard'
            })
            .when('/landing', {
                templateUrl: '/partials/pages/portal/landing.html',
                controller: 'login'
            })
            .when('/create-account', {
                templateUrl: '/partials/pages/portal/create-account.html',
                controller: 'create-account'
            })
            .when('/registration/success', {
                templateUrl: '/partials/pages/portal/registration-success.html'
            })
            .when('/not-found', {
                templateUrl: '/partials/pages/sites/not-found.html'
            })
            .otherwise({
                redirectTo: '/not-found'
            });

        $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

    }])
    .run(function ($rootScope, page, activeMenuItem) {
        $rootScope.page = page;
        $rootScope.activeMenu = activeMenuItem;
    });

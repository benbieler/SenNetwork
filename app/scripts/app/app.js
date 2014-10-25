'use strict';

if (typeof initial === 'undefined') {
    throw new Error('Initial config object (initial) cannot be found!');
}

angular.module(
    'sententiaregum',
    ['ngRoute', 'ngCookies', 'sen.component.page', 'sen.component.dashboard.microblog', 'sen.component.portal.login',
    'sen.component.portal.create-account']
)
    .config(['$routeProvider', '$httpProvider', '$locationProvider',
        function ($routeProvider, $httpProvider, $locationProvider) {

        $locationProvider.hashPrefix('!');

        $routeProvider
            .when('/', {
                templateUrl: '/views/sententiaregum/dashboard.html',
                controller: 'sen-cmp-microblogging'
            })
            .when('/landing', {
                templateUrl: '/views/sententiaregum/login.html',
                controller: 'sen-cmp-login'
            })
            .when('/create-account', {
                templateUrl: '/views/sententiaregum/create-account.html',
                controller: 'sen-cmp-create-account'
            })
            .when('/not-found', {
                templateUrl: '/views/default/not-found.html'
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

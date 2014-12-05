'use strict';

angular.module('sen.app.microblogging.routes', [])
    .config(function ($routeProvider) {

        $routeProvider
            .when('/', {
                templateUrl: '/app/component/application/microblogging/dashboard/dashboard.html',
                controller: 'dashboard'
            });

    });

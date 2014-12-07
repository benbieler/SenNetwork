/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

'use strict';

angular.module('sen.app.microblogging.routes', [])
    .config(function ($routeProvider) {

        $routeProvider
            .when('/', {
                templateUrl: '/app/component/application/microblogging/dashboard/dashboard.html',
                controller: 'dashboard'
            });

    });

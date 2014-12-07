/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

'use strict';

angular.module(
    'sententiaregum',
       ['ngRoute', 'ngCookies', 'ngSanitize', 'infinite-scroll', 'ngResource',
        'sen.app.microblogging.dashboard.microblog', 'sen.app.microblogging.util.post-extractor', 'sen.app.microblogging.util.urlizer',
        'sen.app.page.routes', 'sen.app.microblogging.routes', 'sen.app.user.account.account', 'sen.app.user.account.create-account',
        'sen.app.user.token.login', 'sen.app.user.token.token', 'sen.app.user.routes', 'sen.shared.page.menu-templates',
        'sen.shared.page.page-management', 'sen.shared.security.html-sanitize', 'sen.shared.util.shared-container',
        'sen.shared.util.image-upload', 'sen.app.page.not-found']
)
    .config(['$httpProvider', '$locationProvider',
        function ($httpProvider, $locationProvider) {

            $locationProvider.hashPrefix('!');
            $httpProvider.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

        }
    ])
    .run(function ($rootScope, Page) {
        $rootScope.page = Page;
    });

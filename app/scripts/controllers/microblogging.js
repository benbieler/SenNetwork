'use strict';

angular.module('sen.microblog', [])
    .controller('dashboard', ['$scope', '$cookieStore', '$location', 'authManager',
        function ($scope, $cookieStore, $location, authManager) {

            var token = $cookieStore.get(authManager.tokenCookieKey);
            if (typeof token === 'undefined') {
                $location.path('/landing');
            }

    }]);

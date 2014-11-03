'use strict';

angular.module('sen.microblog', [])
    .controller('dashboard', ['$scope', '$cookieStore', '$location',
        function ($scope, $cookieStore, $location) {

            var token = $cookieStore.get(initial.tokenCookie);
            console.log(token);
            if (null === token || typeof token === 'undefined') {
                $location.path('/landing');
            }

    }]);

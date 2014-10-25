'use strict';

angular.module('sen.component.dashboard.microblog', [])
    .controller('sen-cmp-microblogging', ['$scope', '$cookieStore', '$location',
        function ($scope, $cookieStore, $location) {

            var token = $cookieStore.get(initial.tokenHeader);
            if (null === token || typeof token === 'undefined') {
                $location.path('/landing');
            }

    }]);

'use strict';

angular.module('sen.component.portal.login', [])
    .controller('sen-cmp-login', ['$scope', '$http', 'page', 'activeMenuItem', '$cookieStore', '$location',
        function ($scope, $http, page, activeMenuItem, $cookieStore, $location) {

        page.setPageTitle('Landing');
        activeMenuItem.purgeActiveAttributes(menuTemplates.portal);
        page.setMenuItems(
            activeMenuItem.markItemAsActive(menuTemplates.portal, 0)
        );

        $scope.credentials = {};
        $scope.errors = [];
        $scope.hasError = false;

        $scope.requestToken = function () {
            $scope.progress = true;

            $http.post(
                '/api/request-token',
                { username: $scope.credentials.username, password: $scope.credentials.password }
            )
                .success(function (data) {
                    $cookieStore.set(initial.tokenCookie, data.token);
                    $scope.progress = false;
                    $location.path('/');
                })
                .error(function (data, status) {
                    $scope.hasError = true;

                    switch (parseInt(status)) {
                        case 500:
                            $scope.errors = ['Internal server error occurred. Please try again!'];

                            break;
                        case 401:
                            $scope.errors = data.errors;
                            $scope.credentials.password = '';

                            break;
                        default:
                            throw new Error('Cannot handle server failure with code ' + String(status));
                    }

                    $scope.progress = false;
                });
        };

    }]);

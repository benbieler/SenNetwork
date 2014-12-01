'use strict';

angular.module('sen.login', [])
    .controller('login', ['$scope', '$http', 'page', 'activeMenuItem', '$cookieStore', '$location', 'token', 'tokenModel',
        function ($scope, $http, page, activeMenuItem, $cookieStore, $location, token, tokenModel) {

            var token = $cookieStore.get(token.tokenCookieKey);
            if (typeof token !== 'undefined') {
                $location.path('/');
            }

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

                var credentials = {
                    username: $scope.credentials.username,
                    password: $scope.credentials.password
                };

                tokenModel.auth({}, credentials)
                    .$promise.then(function () {
                        $location.path('/');
                    },
                    function (response) {
                        $scope.progress = false;
                        var data = response.data;
                        var status = parseInt(response.status);

                        switch (status) {
                            // token request has been refused by the server.
                            // the occurred errors will be shown
                            case 401:
                                $scope.errors = data.errors;
                                $scope.hasError = true;

                                break;
                            // any unknown error
                            // unknown error message will be shown
                            default:
                                $scope.errors = ['Internal server error occurred. Please try again'];

                                break;
                        }

                        $scope.credentials.password = '';
                    }
                );
            };
        }]
    );

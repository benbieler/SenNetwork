'use strict';

angular.module('sen.login', [])
    .controller('login', ['$scope', '$http', 'page', 'activeMenuItem', '$cookieStore', '$location', 'authManager',
        function ($scope, $http, page, activeMenuItem, $cookieStore, $location, authManager) {

            var token = $cookieStore.get(authManager.tokenCookieKey);
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

                var promise = $http.post('/api/request-token', credentials);
                authManager.handleLogin(promise, '/');

                promise
                    .error(function (data, status) {
                        console.log(status);
                        console.log(data);
                        $scope.hasError = true;

                        // 500 error (internal server error):
                        // this error occurs if anything on the http server
                        // went wrong
                        if (500 === parseInt(status)) {
                            $scope.errors = ['Internal server error occurred. Please try again!'];
                            $scope.credentials.password = '';
                            $scope.progress = false;

                            return;
                        }

                        // 401 error (unauthorized):
                        // this error occurs if the given credentials are invalid
                        // and refused by the server
                        if (401 === parseInt(status)) {
                            $scope.errors = data.errors;
                            $scope.credentials.password = '';
                            $scope.progress = false;

                            return;
                        }

                        // if any else error occurs, an error will be thrown
                        if (![401, 500].contains(status)) {
                            throw new Error('Cannot handle server failure with code ' + String(status));
                        }
                    }
                );
            };

    }])
    .factory('authManager', function ($cookieStore, $location) {

        return {

            tokenCookieKey: 'sen-token-id',
            tokenHeader: 'X-SEN-USER-TOKEN',

            /**
             * Attaches a login handler on a auth promise
             * @param promise
             * @param loginRedirect
             */
            handleLogin: function (promise, loginRedirect) {
                var cookieKey = this.tokenCookieKey;

                promise.success(
                    function (data) {
                        $cookieStore.put(cookieKey, data.token);
                        $location.path(loginRedirect);
                    }
                );
            },

            /**
             * Handles the logout
             * @param redirectPath
             */
            logout: function (redirectPath) {
                $cookieStore.remove(this.tokenCookieKey);

                if (null !== redirectPath && typeof redirectPath !== 'undefined') {
                    $location.path(redirectPath);
                }
            }

        }
    });

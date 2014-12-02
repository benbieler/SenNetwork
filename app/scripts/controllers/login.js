'use strict';

angular.module('sen.login', [])
    .controller('login', ['$scope', '$http', 'Page', 'ActiveMenuItem', '$cookieStore', '$location', 'Token', 'TokenModel',
        function ($scope, $http, Page, ActiveMenuItem, $cookieStore, $location, Token, TokenModel) {

            var token = $cookieStore.get(Token.tokenCookieKey);
            if (typeof token !== 'undefined') {
                $location.path('/');
            }

            Page.setPageTitle('Landing');
            ActiveMenuItem.purgeActiveAttributes(menuTemplates.portal);
            Page.setMenuItems(
                ActiveMenuItem.markItemAsActive(menuTemplates.portal, 0)
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

                TokenModel.auth(
                    {}, credentials,
                    function () {
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

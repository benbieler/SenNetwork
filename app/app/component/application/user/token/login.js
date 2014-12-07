/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

'use strict';

angular.module('sen.app.user.token.login', [])
    .controller('login', ['$scope', '$http', 'Page', 'ActiveMenuItem', '$cookieStore', '$location', 'Token', 'TokenModel', 'MenuTemplates',
        function ($scope, $http, Page, ActiveMenuItem, $cookieStore, $location, Token, TokenModel, MenuTemplates) {

            var token = $cookieStore.get(Token.tokenCookieKey);
            if (typeof token !== 'undefined') {
                $location.path('/');
            }

            Page.setPageTitle('Landing');
            ActiveMenuItem.purgeActiveAttributes(MenuTemplates.portal);
            Page.setMenuItems(
                ActiveMenuItem.markItemAsActive(MenuTemplates.portal, 0)
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
                                $scope.errors = ['Internal server error occurred. Please try again later'];
                                break;
                        }

                        $scope.credentials.password = '';
                    }
                );
            };
        }]
    );

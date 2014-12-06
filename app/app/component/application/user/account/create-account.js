'use strict';

angular.module('sen.app.user.account.create-account', [])
    .controller('create-account', ['$scope', '$location', 'Page', 'ActiveMenuItem', 'Account', 'MenuTemplates',
        function ($scope, $location, Page, ActiveMenuItem, Account, MenuTemplates) {

            $scope.progress = false;

            // setup page
            Page.setPageTitle('Create account');

            ActiveMenuItem.purgeActiveAttributes(MenuTemplates.portal);
            Page.setMenuItems(ActiveMenuItem.markItemAsActive(MenuTemplates.portal, 1));

            // initialize model wrapper
            $scope.data = {};

            $scope.createAccount = function () {
                $scope.progress = true;

                var errors = {};

                // validate password and email confirmation
                if ($scope.data.password !== $scope.data.passwordConfirm) {
                    errors.passwordConfirmation = ['The password does not match the confirmation'];
                }
                if ($scope.data.email !== $scope.data.emailConfirm) {
                    errors.emailConfirmation = ['The email address does not match its confirmation'];
                }

                if (typeof errors.emailConfirmation !== 'undefined'
                    || typeof errors.passwordConfirmation !== 'undefined') {

                    $scope.progress = false;
                    $scope.errorList = errors;
                }

                var userInput = {
                    username: $scope.data.name,
                    password: $scope.data.password,
                    email: $scope.data.email,
                    realname: $scope.data.realname
                };

                Account.create(
                    {}, userInput,
                    function (data) {
                        $scope.progress = false;

                        if (typeof data.username !== 'undefined') {
                            $location.path('/registration/success');
                            return;
                        }

                        $scope.errorList = data.errors;

                        // clear password and password confirmation fields for security reasons
                        $scope.data.password = '';
                        $scope.data.passwordConfirm = '';
                    }
                );
            };
        }
    ]);

'use strict';

angular.module('sen.registration', [])
    .controller('create-account', ['$scope', 'page', 'activeMenuItem',
        function ($scope, page, activeMenuItem) {

            $scope.progress = false;

            // setup page
            page.setPageTitle('Create account');
            activeMenuItem.purgeActiveAttributes(menuTemplates.portal);
            page.setMenuItems(activeMenuItem.markItemAsActive(menuTemplates.portal, 1));

            // initialize model wrapper
            $scope.data = {};

            $scope.createAccount = function () {
                function handleFailure(result) {

                    // inject errors into scope
                    $scope.errorList = result;
                }

                $scope.progress = true;

                // validate password and email confirmation on client side
                function hasConfirmationErrors(object) {
                    return typeof object.passwordConfirmation !== 'undefined'
                        || typeof object.emailConfirmation !== 'undefined';
                }

                var confirmationErrors = {};
                if ($scope.data.password !== $scope.data.passwordConfirm) {
                    confirmationErrors.passwordConfirmation = ['The password does not match the confirmation'];
                }
                if ($scope.data.email !== $scope.data.emailConfirm) {
                    confirmationErrors.emailConfirmation = ['The email address does not match its confirmation'];
                }

                if (hasConfirmationErrors(confirmationErrors)) {
                    handleFailure(confirmationErrors);
                } else {

                    // clear error list, if it's already filled
                    $scope.errorList = {};
                }

                // send account and validation request to server
                // @ToDo: send account creation request to the server

                $scope.progress = false;
            };
        }
    ]);

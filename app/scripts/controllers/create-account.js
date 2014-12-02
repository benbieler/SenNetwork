'use strict';

angular.module('sen.registration', [])
    .controller('create-account', ['$scope', '$http', '$location', 'Page', 'ActiveMenuItem',
        function ($scope, $http, $location, Page, ActiveMenuItem) {

            $scope.progress = false;

            // setup page
            Page.setPageTitle('Create account');
            ActiveMenuItem.purgeActiveAttributes(menuTemplates.portal);
            Page.setMenuItems(ActiveMenuItem.markItemAsActive(menuTemplates.portal, 1));

            // initialize model wrapper
            $scope.data = {};

            $scope.createAccount = function () {
                function handleFailure(result) {

                    // inject errors into scope
                    $scope.errorList = result;

                    // clear password and password confirm fields for security reason
                    $scope.data.password = '';
                    $scope.data.passwordConfirm = '';
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
                    $scope.progress = false;

                    return;
                } else {

                    // clear error list, if it's already filled
                    $scope.errorList = {};
                }

                // send account and validation request to server
                $http({
                    url: '/api/create-account',
                    data: {
                        username: $scope.data.name,
                        password: $scope.data.password,
                        email: $scope.data.email,
                        realname: $scope.data.realname
                    },
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' }
                })
                    .success(function (data) {

                        if (typeof data.username !== 'undefined') {
                            handleSuccess();
                            $scope.progress = false;
                            return;
                        }

                        $scope.progress = false;
                        handleFailure(data.errors);

                    });

                function handleSuccess() {
                    $location.path('/registration/success');
                }
            };
        }
    ]);

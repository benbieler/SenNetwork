'use strict';

angular.module('sen.component.portal.create-account', [])
    .controller('sen-cmp-create-account', ['$scope', 'page', 'activeMenuItem',
        function ($scope, page, activeMenuItem) {

            $scope.progress = false;
            page.setPageTitle('Create account');
            activeMenuItem.purgeActiveAttributes(menuTemplates.portal);
            page.setMenuItems(activeMenuItem.markItemAsActive(menuTemplates.portal, 1));

            $scope.createAccount = function () {
                var validationErrors = {};

                function handleError(errorList) {

                }
            };
        }
    ]);

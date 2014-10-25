'use strict';

angular.module('sen.component.portal.create-account', [])
    .controller('sen-cmp-create-account', ['$scope', 'page', 'activeMenuItem',
        function ($scope, page, activeMenuItem) {

            $scope.progress = false;
            page.setPageTitle('Create account');
            activeMenuItem.purgeActiveAttributes(menuTemplates.portal);
            page.setMenuItems(activeMenuItem.markItemAsActive(menuTemplates.portal, 1));

            $scope.createAccount = function () {

            };

            $scope.getPasswordStrengthMessage = function () {
                $scope.calculateStrength = true;
                var securePoints = 0;

                var password = String($scope.data.password);

                // increase points for "secure" characters
                var secureChars = ['^', '$', '!', '"', '§', '%', '&', '/', '?', '+', '*', '<', '>', '|', '.', '-', '_'];

                for (var key in secureChars) {
                    var str = secureChars[key];
                    var occurrences = 0;

                    if (password.indexOf(str) === -1) {
                        var noSecureChars = true;
                    } else {
                        var noSecureChars = false;
                    }

                    while (password.indexOf(str) !== -1) {
                        // no character should appear more than five times
                        // if this happens, the secure points will be decreased
                        if (5 === occurrences) {
                            securePoints--;
                            continue;
                        }

                        securePoints++;
                        occurrences++;
                    }
                }

                // increase for numbers
                var chars = password.match(/\d+/).length;
                securePoints += Math.floor(chars / 4);

                // increase points for string length
                var passLen = password.length;

                if (0 !== passLen) {
                    if (passLen <= 100) {
                        securePoints += Math.floor(passLen / 4);
                    } else {
                        securePoints += Math.floor(passLen / 8);
                    }
                }

                // if there are not secure chars, the secure point count will be decreased
                // this should avoid, that passwords like simple 20 character passwords will be classified
                // as "Very strong"
                if (noSecureChars) {
                    if (securePoints <= 10) {
                        securePoints = securePoints / 2;
                    } else if (securePoints <= 50) {
                        securePoints = securePoints / 4;
                    } else {
                        securePoints = securePoints / 8;
                    }
                }

                // return messages for strength
                switch (true) {
                    case securePoints >= 0:
                        var msg = 'Very weak';

                        break;
                    case securePoints > 2:
                        var msg = 'Weak';

                        break;
                    case securePoints > 5:
                        var msg = 'Strong';

                        break;
                    case securePoints > 8:
                        var msg = 'Very strong';

                        break;
                    case securePoints > 20:
                        var msg = 'Extremly strong';

                        break;
                }

                $scope.calculateStrength = false;
                return msg;
            };

        }
    ]);

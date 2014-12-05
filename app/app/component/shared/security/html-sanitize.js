'use strict';

angular.module('sen.shared.security.html-sanitize', [])
    .factory('HTMLSanitize', function () {

        return function (input) {
            var dataToReplace = {
                '<': '&lt;',
                '>': '&gt',
                '&': '&amp;'
            };

            for (var danger in dataToReplace) {
                var secure = dataToReplace[danger];

                input.replace(danger, secure);
            }

            return input;
        };

    });

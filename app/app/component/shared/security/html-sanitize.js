/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

'use strict';

angular.module('sen.shared.security.html-sanitize', [])
    .factory('HTMLSanitize', function () {

        /**
         * Sanitizes a string
         *
         * @param input
         * @returns string
         */
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

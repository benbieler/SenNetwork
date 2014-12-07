/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

'use strict';

angular.module('sen.app.microblogging.util.urlizer', [])
    .factory('URLify', function () {

        /**
         * @param text
         * @returns string
         */
        return function (text) {
            var RegExpr = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;

            return text.replace(RegExpr, '<a href="$1" target="_blank">$1</a>');
        };

    });

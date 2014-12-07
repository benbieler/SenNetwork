/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

'use strict';

angular.module('sen.app.microblogging.util.post-extractor', [])
    .factory('ExtractNames', function () {



    })
    .factory('ExtractTags', function () {



    })
    .factory('ReplaceNames', function () {



    })
    .factory('ReplaceTags', function () {

        /**
         * @param text
         * @param args
         * @returns string
         */
        return function (text, tags) {
            if (typeof tags !== 'array') {
                throw new TypeError('Tag list must be type of array');
            }

            for (var i in tags) {
                text.replace('#' + tags[i], '<a href="/#!/tag/"' + tags[i] + '>#' + tags[i] + '</a>');
            }

            return text;
        };

    });

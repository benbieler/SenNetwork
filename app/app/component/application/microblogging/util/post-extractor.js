'use strict';

angular.module('sen.app.microblogging.util.post-extractor', [])
    .factory('ExtractNames', function () {



    })
    .factory('ExtractTags', function () {



    })
    .factory('ReplaceNames', function () {



    })
    .factory('ReplaceTags', function () {

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

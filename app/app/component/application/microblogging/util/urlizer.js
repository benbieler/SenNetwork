'use strict';

angular.module('sen.app.microblogging.util.urlizer', [])
    .factory('URLify', function () {

        return function (text) {
            var RegExpr = /(\b(https?|ftp|file):\/\/[-A-Z0-9+&@#\/%?=~_|!:,.;]*[-A-Z0-9+&@#\/%=~_|])/ig;

            return text.replace(RegExpr, '<a href="$1" target="_blank">$1</a>');
        };

    });

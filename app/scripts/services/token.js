'use strict';

angular.module('sen.service.token', [])
    .factory('TokenModel', function ($resource, Token) {

        var cookieAuthInterceptor = {
            response: function (response) {
                var data = response.data;
                var result = Token.requestInterceptor(data);
                if (null !== result && typeof result !== 'undefined') {
                    return result;
                }
            }
        };

        return $resource(
            '/api/token/:token', {'token': '@tokenId'},
            {
                'auth': {
                    method: 'POST',
                    interceptor: cookieAuthInterceptor
                }
                // @ToDo: add logout resource
            }
        );

    })
    .factory('Token', function ($cookieStore, $location) {

        return {

            tokenCookieKey: 'sen-token-id',
            tokenHeader: 'X-SEN-USER-TOKEN',
            redirectPath: '/landing',

            requestInterceptor: function (data) {
                if (typeof data.token !== 'undefined') {
                    $cookieStore.put(this.tokenCookieKey, data.token);
                }

                return data;
            },

            flushToken: function (logoutPath) {
                // @ToDo: send request deletion request to server (logout route in symfony is required)

                $cookieStore.remove(this.tokenCookieKey);
                $location.path(logoutPath);
            }

        };

    });
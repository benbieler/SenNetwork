'use strict';

angular.module('sen.service.token', [])
    .factory('tokenModel', function ($resource, token) {

        return $resource(
            '/api/token', {},
            {
                'auth': {
                    method: 'POST',
                    interceptor: {
                        response: function (data) {
                            var result = token.requestInterceptor(data);
                            if (null !== result && typeof result !== 'undefined') {
                                return result;
                            }
                        }
                    }
                }
                // @ToDo: add logout resource
            }
        );

    })
    .factory('token', function ($cookieStore, $location) {

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

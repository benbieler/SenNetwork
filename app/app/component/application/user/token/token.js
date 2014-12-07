/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

'use strict';

angular.module('sen.app.user.token.token', [])
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

            /**
             * @type string
             */
            tokenCookieKey: 'sen-token-id',

            /**
             * @type string
             */
            tokenHeader: 'X-SEN-USER-TOKEN',

            /**
             * @type string
             */
            redirectPath: '/landing',

            /**
             * Interceptor which hooks into the request token action
             *
             * @param data
             * @returns {*}
             */
            requestInterceptor: function (data) {
                if (typeof data.token !== 'undefined') {
                    $cookieStore.put(this.tokenCookieKey, data.token);
                }

                return data;
            },

            /**
             * Action which flushes the API token
             *
             * @param logoutPath
             */
            flushToken: function (logoutPath) {
                // @ToDo: send request deletion request to server (logout route in symfony is required)

                $cookieStore.remove(this.tokenCookieKey);
                $location.path(logoutPath);
            }

        };

    });

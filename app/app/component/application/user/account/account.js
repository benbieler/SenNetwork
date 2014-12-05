'use strict';

angular.module('sen.app.user.account.account', [])
    .factory('Account', function ($resource) {

        return $resource(
            '/api/account/:id',
            { id: '@id' },
            {
                'create': {
                    method: 'POST'
                }
            }
        );

    });

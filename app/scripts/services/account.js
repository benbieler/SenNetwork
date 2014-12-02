'use strict';

angular.module('sen.service.account', [])
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

'use strict';

angular.module('sen.shared.page.menu-templates', [])
    .factory('MenuTemplates', function () {

        /**
         * This object contains the template of our menu
         */
        return {
            portal: [
                {
                    label: 'Landing',
                    target: '/#!/landing'
                },
                {
                    label: 'Registration',
                    target: '/#!/create-account'
                },
                {
                    label: 'Imprint',
                    target: '/#!/imprint'
                },
                {
                    label: 'Terms & Conditions',
                    target: '/#!/terms-and-conditions'
                }
            ],
            application: [
                {
                    label: 'Dashboard',
                    target: '/#!/'
                },
                {
                    label: 'Messenger',
                    target: '/#!/chat'
                },
                {
                    label: 'Find a user',
                    target: '/#!/search-members'
                },
                {
                    label: 'Imprint',
                    target: '/#!/imprint'
                },
                {
                    label: 'Terms & Conditions',
                    target: '/#!/terms-and-conditions'
                },
                {
                    label: 'Logout',
                    target: '/#!/logout'
                }
            ]
        };

    });

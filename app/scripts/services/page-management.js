'use strict';

angular.module('sen.page', [])
    .factory('page', function () {
        var viewModel = [];

        return {
            setPageTitle: function (newTitle) {
                viewModel['page.title'] = String(newTitle);
                return this;
            },
            getPageTitle: function () {
                return viewModel['page.title'];
            },
            setMenuItems: function (items) {
                viewModel['page.menu'] = items;
                return this;
            },
            getMenuItems: function () {
                return viewModel['page.menu'];
            }
        };
    })
    .factory('activeMenuItem', function () {
        return {
            recentElements: [],
            markItemAsActive: function (elements, index) {
                if (typeof elements[index] === 'undefined') {
                    throw new Error('Index ' + index + ' not found in menu element list!');
                }

                var data = elements[index];
                data.active = true;

                elements[index] = data;
                this.recentElements = elements;

                return elements;
            },
            purgeActiveAttributes: function (elements) {
                if (typeof elements === 'undefined') {
                    var elements = this.recentElements;
                }

                for (var i in elements) {
                    if (typeof elements[i].active !== 'undefined') {
                        elements[i].active = false;
                    }
                }

                return elements;
            }
        };
    });

/**
 * Object containing the menu items
 *
 * @type {{portal: {label: string, target: string}[]}}
 */
var menuTemplates = {
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

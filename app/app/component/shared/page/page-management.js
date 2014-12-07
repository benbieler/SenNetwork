/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

'use strict';

angular.module('sen.shared.page.page-management', [])
    .factory('Page', function () {
        var viewModel = [];

        return {
            /**
             * Sets the page title
             *
             * @param newTitle
             * @returns {*}
             */
            setPageTitle: function (newTitle) {
                viewModel['page.title'] = String(newTitle);
                return this;
            },

            /**
             * Returns the page title
             *
             * @returns {*}
             */
            getPageTitle: function () {
                return viewModel['page.title'];
            },

            /**
             * Sets the menu items
             *
             * @param items
             * @returns {*}
             */
            setMenuItems: function (items) {
                viewModel['page.menu'] = items;
                return this;
            },

            /**
             * returns the menu items
             *
             * @returns {*}
             */
            getMenuItems: function () {
                return viewModel['page.menu'];
            }
        };
    })
    .factory('ActiveMenuItem', function () {
        return {
            /**
             * @type {{string}}
             */
            recentElements: [],

            /**
             * Attaches the active attribute on a given item in the elements array
             *
             * @param elements
             * @param index
             * @returns {*}
             */
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

            /**
             * Purges the active attributes of the items in the elements array
             *
             * @param elements
             * @returns {*}
             */
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

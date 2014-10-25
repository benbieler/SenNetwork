'use strict';

angular.module('sen.component.page', [])
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
                var length = elements.length;

                for (var i = 0; i < length; i++) {
                    if (typeof elements[i].active !== 'undefined') {
                        elements[i].active = false;
                    }
                }

                return elements;
            }
        };
    });

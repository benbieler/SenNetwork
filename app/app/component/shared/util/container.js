/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

'use strict';

angular.module('sen.shared.util.shared-container', [])
    .factory('DataContainer', function () {

        return {

            /**
             * @type mixed
             */
            data: null,

            /**
             * @type {function}
             */
            notifiers: [],

            /**
             * Returns the preserved data
             *
             * @returns {*}
             */
            get preservedData() {
                if (null === this.data) {
                    return {};
                }

                return this.data;
            },

            /**
             * Sets the preserved data
             *
             * @param dataToPreserve
             */
            set preservedData(dataToPreserve) {
                this.data = dataToPreserve;
                this.notifiers.forEach(
                    function (callable) {
                        callable(dataToPreserve);
                    }
                );

                return this;
            },

            /**
             * Attaches a notifier
             *
             * @param notifier
             * @returns {*}
             */
            pushNotifier: function (notifier) {
                if (typeof notifier !== 'function') {
                    throw new TypeError('Notifier must be a function');
                }

                this.notifiers.push(notifier);
                return this;
            }

        };

    });

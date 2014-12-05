'use strict';

angular.module('sen.shared.util.shared-container', [])
    .factory('DataContainer', function () {

        return {

            data: null,
            notifiers: [],

            get preservedData() {
                if (null === this.data) {
                    return {};
                }

                return this.data;
            },

            set preservedData(dataToPreserve) {
                this.data = dataToPreserve;
                this.notifiers.forEach(
                    function (callable) {
                        callable(dataToPreserve);
                    }
                );

                return this;
            },

            pushNotifier: function (notifier) {
                if (typeof notifier !== 'function') {
                    throw new TypeError('Notifier must be a function');
                }

                this.notifiers.push(notifier);
                return this;
            }

        };

    });

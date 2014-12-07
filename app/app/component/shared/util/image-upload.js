/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

'use strict';

if (typeof window.jQuery === 'undefined') {
    throw new Error('This directive requires jQuery');
}

(function ($) {

    $.uploadButton = function (element, fileUploadId) {

        $(element).click(function () {
            $('#' + fileUploadId).click();
        });

    };

})(jQuery);

angular.module('sen.shared.util.image-upload', [])
    .directive('imageUploadButton', function () {

        return {
            /**
             * @type string
             */
            restrict: 'E',

            /**
             * @type boolean
             */
            replace: true,

            /**
             * @type boolean
             */
            transclude: false,

            /**
             * compiles the custom tag
             *
             * @param el
             * @param attrs
             */
            compile: function (el, attrs) {

                var template = '<button type="button" class="' + attrs.class + '">Upload image!</button>';
                el.replaceWith(template);

                return function (scope, element, attrs, controller) {

                    $.uploadButton(element, 'imageUpload');

                }

            }
        };

    });

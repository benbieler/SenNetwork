'use strict';

(function ($) {

    $.uploadButton = function (element, fileUploadId) {

        $(element).click(function () {
            $('#' + fileUploadId).click();
        });

    };

})(jQuery);

angular.module('sen.image-upload', [])
    .directive('imageUploadButton', function () {

        return {
            restrict: 'E',
            replace: true,
            transclude: false,

            /**
             * compiles the custom tag
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
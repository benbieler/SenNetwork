'use strict';

angular.module('sen.app.page.not-found', [])
    .controller('notFound', ['ActiveMenuItem', 'Page', function (ActiveMenuItem, Page) {
        Page.setMenuItems(ActiveMenuItem.purgeActiveAttributes());
    }]);

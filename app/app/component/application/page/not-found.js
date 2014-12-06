'use strict';

angular.module('sen.app.page.not-found', [])
    .controller('notFound', ['ActiveMenuItem', 'Page', function (ActiveMenuItem, Page) {
        var currentItems = Page.getMenuItems();
        Page.setMenuItems(ActiveMenuItem.purgeActiveAttributes(currentItems));
    }]);

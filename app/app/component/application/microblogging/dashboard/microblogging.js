/*
 * This file is part of the sententiaregum application.
 *
 * Sententiaregum is a social network based on Symfony2 and AngularJS
 *
 * @copyright (c) 2014 Sententiaregum
 * Please check out the license file in the document root of this application
 */

'use strict';

angular.module('sen.app.microblogging.dashboard.microblog', ['infinite-scroll'])
    .controller('dashboard', ['$scope', '$cookieStore', '$location', 'Token', 'Page', 'ActiveMenuItem', 'MenuTemplates',
        function ($scope, $cookieStore, $location, Token, Page, ActiveMenuItem, MenuTemplates) {

            Page.setPageTitle('Dashboard');

            ActiveMenuItem.purgeActiveAttributes(MenuTemplates.application);
            Page.setMenuItems(ActiveMenuItem.markItemAsActive(MenuTemplates.application, 0));
            var token = $cookieStore.get(Token.tokenCookieKey);
            if (typeof token === 'undefined') {
                $location.path('/landing');
            }

            $scope.posts = [
                {
                    id: 1,
                    avatarUrl: '/images/avatar.jpg',
                    content: 'a little post #foo #bar',
                    realname: 'Maxi',
                    username: 'Ma27',
                    tags: ['foo', 'bar'],
                    shares: [
                        'admin', 'foo'
                    ],
                    comments: [],
                    creationDate: '2014/12/24 11:11:11',
                    head: 'Ma27 has commented'
                }
            ];

            $scope.addItems = function () {
                $scope.posts.push({
                    id: 2,
                    avatarUrl: '/images/avatar.jpg',
                    content: 'another post <script type="text/javascript">alert("XSS");</script> <h1>foo</h1> < #bar',
                    realname: 'admin',
                    username: 'admin',
                    tags: ['bar'],
                    shares: [
                        'foo'
                    ],
                    comments: [
                        {
                            name: 'Ma27',
                            comment: 'foobar'
                        }
                    ],
                    creationDate: '2014/11/20 11:11:11'
                });
            };

            $scope.buildDate = function (dateTimeStr) {

                var date = new Date(String(dateTimeStr));
                var months = [
                    'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'
                ];

                return date.getDate() + ' ' + months[date.getMonth()];

            };

            $scope.showComments = false;

    }])
    .controller('favTagList', ['$scope', function ($scope) {

        // setting mock values to test the GUI
        $scope.tags = [
            {
                name: 'foobar',
                id: 1
            },
            {
                name: 'foo',
                id: 2
            },
            {
                name: 'bar',
                id: 3
            },
            {
                name: 'baz',
                id: 4
            },
            {
                name: 'foobarbaz',
                id: 5
            }
        ];

    }]);

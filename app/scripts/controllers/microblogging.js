'use strict';

angular.module('sen.microblog', [])
    .controller('dashboard', ['$scope', '$cookieStore', '$location', 'authManager', 'page', 'activeMenuItem',
        function ($scope, $cookieStore, $location, authManager, page, activeMenuItem) {

            page.setPageTitle('Dashboard');
            activeMenuItem.purgeActiveAttributes(menuTemplates.application);
            page.setMenuItems(activeMenuItem.markItemAsActive(menuTemplates.application, 0));
            var token = $cookieStore.get(authManager.tokenCookieKey);
            if (typeof token === 'undefined') {
                $location.path('/landing');
            }

            // mock values
            var posts = [
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

            $scope.posts = posts;

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

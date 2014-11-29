'use strict';

module.exports = function (grunt) {

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        htmlmin: {
            options: {
                removeComments: true,
                collapseWhitespace: true
            },
            dist: {
                files: [{
                    expand: true,
                    cwd: 'app/',
                    src: ['*.html', 'partials/*/*/*.html'],
                    dest: 'dist/'
                }]
            }
        },
        sass: {
            dist: {
                files: [{
                    expand: true,
                    cwd: 'app/styles',
                    src: ['*.scss'],
                    dest: 'dist/styles',
                    ext: '.css'
                }]
            }
        },
        concat: {
            css: {
                src: [
                    'app/scripts/vendor/bootstrap/dist/css/bootstrap.min.css',
                    'app/scripts/vendor/perfect-scrollbar/src/perfect-scrollbar.css',
                    'dist/styles/*.css'
                ],
                dest: 'dist/all.css'
            },
            js: {
                src: [
                    'app/scripts/vendor/jquery/jquery.min.js',
                    'app/scripts/vendor/bootstrap/dist/js/bootstrap.min.js',
                    'app/scripts/vendor/perfect-scrollbar/min/perfect-scrollbar.min.js',
                    'app/scripts/vendor/angular/angular.min.js',
                    'app/scripts/vendor/angular-route/angular-route.min.js',
                    'app/scripts/vendor/angular-cookies/angular-cookies.min.js',
                    'app/scripts/vendor/angular-sanitize/angular-sanitize.min.js',
                    'app/scripts/vendor/ngInfiniteScroll/build/ng-infinite-scroll.min.js',
                    'app/scripts/app.js',
                    'app/scripts/*/*.js'
                ],
                dest: 'dist/all.js'
            }
        },
        mkdir: {
            all: {
                options: {
                    create: ['dist']
                }
            }
        },
        cssmin: {
            combine: {
                files: {
                    'dist/all.css': ['dist/all.css']
                }
            }
        },
        uglify: {
            options: {
                mangle: false
            },
            dist: {
                files: {
                    'dist/all.js': [
                        'dist/all.js'
                    ]
                }
            }
        },
        copy: {
            main: {
                files: [
                    {
                        expand: true,
                        cwd: 'app/',
                        src: 'robots.txt',
                        dest: 'dist/'
                    },
                    {
                        expand: true,
                        cwd: 'app/',
                        src: 'sitemap.xml',
                        dest: 'dist/'
                    },
                    {
                        expand: true,
                        cwd: 'app/images/',
                        src: '*',
                        dest: 'dist/images/'
                    }
                ]
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-htmlmin');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-mkdir');
    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-htmlmin');
    grunt.loadNpmTasks('grunt-contrib-copy');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-sass');

    grunt.registerTask('build-production', [
        'sass',
        'mkdir',
        'htmlmin',
        'concat',
        'cssmin',
        'copy',
        'uglify'
    ]);

    grunt.registerTask('default', ['build-production']);

};

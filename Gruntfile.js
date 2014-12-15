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
                    src: ['index.html', 'app/component/application/*/*/*.html'],
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
                    'app/app/vendor/bootstrap/dist/css/bootstrap.min.css',
                    'app/app/vendor/perfect-scrollbar/src/perfect-scrollbar.css',
                    'dist/styles/*.css'
                ],
                dest: 'dist/all.css'
            },
            js: {
                src: [
                    'app/app/vendor/jquery/jquery.min.js',
                    'app/app/vendor/bootstrap/dist/js/bootstrap.min.js',
                    'app/app/vendor/perfect-scrollbar/min/perfect-scrollbar.min.js',
                    'app/app/vendor/angular/angular.min.js',
                    'app/app/vendor/angular-route/angular-route.min.js',
                    'app/app/vendor/angular-cookies/angular-cookies.min.js',
                    'app/app/vendor/angular-sanitize/angular-sanitize.min.js',
                    'app/app/vendor/angular-resource/angular-resource.min.js',
                    'app/app/vendor/ngInfiniteScroll/build/ng-infinite-scroll.min.js',
                    'app/app/app.js',
                    'app/app/component/shared/*/*.js',
                    'app/app/component/application/*/*.js',
                    'app/app/component/application/*/*/*.js'
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
                    },
                    {
                        expand: true,
                        cwd: 'app/',
                        src: 'favicon.ico',
                        dest: 'dist/'
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

    grunt.registerTask('build', [
        'sass',
        'mkdir',
        'htmlmin',
        'concat',
        'cssmin',
        'copy'
    ]);

    grunt.registerTask('build-production', ['build', 'uglify']);

    grunt.registerTask('default', ['build-production']);

};

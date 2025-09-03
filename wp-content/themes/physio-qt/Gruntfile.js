'use strict';
module.exports = function(grunt) {

    // load all grunt tasks matching the `grunt-*` pattern
    require('load-grunt-tasks')(grunt);

    grunt.initConfig({

        pgk: grunt.file.readJSON( 'package.json' ),

        // https://github.com/gruntjs/grunt-contrib-watch
        watch: {
            compass: {
                files: ['assets/sass/**/*.scss', '*.php'],
                tasks: ['compass','autoprefixer']
            },
            js: {
                files: ['Gruntfile.js','assets/js/*.js'],
                tasks: ['requirejs:compile', 'jshint']
            }
        },

        // https://github.com/sindresorhus/grunt-sass
        sass: {
            dist: {
                options: {
                    style: 'expanded',
                    sourcemap: 'none'
                },
                files: {
                    'woocommerce.css': 'assets/sass/woocommerce.scss',
                    'assets/css/bootstrap.css': 'assets/sass/bootstrap.scss',
                    'editor-style.css': 'assets/sass/editor-style.scss',
                    'rtl.css': 'assets/sass/rtl.scss',
                    'style.css': 'assets/sass/style.scss',
                }
            }
        },

        // https://github.com/nDmitry/grunt-autoprefixer
        autoprefixer: {
            options: {
                browsers: ['last 2 versions', 'ie 9', 'ie 10']
            },
            files: {
                expand: true,
                flatten: true,
                src: '*.css',
                dest: ''
            }
        },

        // https://github.com/gruntjs/grunt-contrib-jshint
        jshint: {
            options: {
                "bitwise": true,
                "browser": true,
                "curly": true,
                "eqeqeq": true,
                "eqnull": true,
                "es5": true,
                "esnext": true,
                "immed": true,
                "jquery": true,
                "latedef": true,
                "newcap": true,
                "noarg": true,
                "node": true,
                "strict": false,
                "undef": true,
                "globals": {
                    "jQuery": true,
                    "alert": true,
                    "google": true,
                    "InfoBox": true,
                    "themeajax": true,
                    "ajaxurl": true,
                    "Favico": true,
                    "BackgroundCheck": true,
                    "smoothScroll": true
                }
            }
        },

        copy: {
            main: {
                expand: true,
                dot: false,
                flatten: false,
                src: [
                    '*.css',
                    '*.php',
                    'Gruntfile.js',
                    'package.json',
                    'bower.json',
                    'wpml-config.xml',
                    'screenshot.{jpg,png}',
                    'readme.txt',
                    'assets/**',
                    'bundled-plugins/**',
                    'bower_components/bootstrap-sass/assets/**',
                    'bower_components/fontawesome/css/font-awesome.min.css',
                    'bower_components/fontawesome/fonts/**',
                    'bower_components/almond/almond.js',
                    'bower_components/requirejs/require.js',
                    'bower_components/picturefill/**',
                    'demo-files/**',
                    'inc/**',
                    'languages/**',
                    'template-parts/**',
                    'woocommerce/**',
                ],
                dest: 'physio-qt/',
            },
        },

        compress: {
            main: {
                options: {
                    archive: 'physio-qt.zip',
                    mode:    'zip'
                },
                src: 'physio-qt/**'
            }
        },
        
        // https://github.com/gruntjs/grunt-contrib-requirejs
        requirejs: {
            compile: {
                options: {
                    baseUrl: '',
                    mainConfigFile: 'assets/js/main.js',
                    optimize: 'uglify2',
                    preserveLicenseComments: false,
                    wrap: true,
                    useStrict: true,
                    name: 'bower_components/almond/almond',
                    include: 'assets/js/main',
                    out: 'assets/js/main.min.js'
                }
            }
        },

        makepot: {
            target: {
                options: {
                    domainPath: '/languages/',
                    mainFile: 'physio-qt.pot',
                    type: 'wp-theme',
                    exclude: [
                        'inc/envato-updater/.*',
                        'inc/radium-one-click-demo-install/.*',
                        'node_modules/.*',
                        'bower_components/.*',
                        'bundled-plugins/.*',
                    ]
                }
            }
        },

        // https://www.npmjs.com/package/grunt-browser-sync
        browserSync: {
            options: {
                proxy: 'qt.dev/dev/physio',
                reloadDelay: 0,
                watchTask: true,
                online: true
            },
            bsFiles: {
                src: [
                    '*.php',
                    '*.css',
                    '*.scss',
                    'inc/*.php',
                    'woocommerce/*.php',
                    'assets/js/*.js'
                ]
            },
        },

    });

    grunt.registerTask('check', [
        'browserSync',
        'watch'
    ]);

    // register task
    grunt.registerTask('default', [
        'copy',
        'compress',
        'compass',
        'autoprefixer',
        'requirejs',
        'browserSync',
        'watch'
    ]);

};
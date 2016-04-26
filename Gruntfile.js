module.exports = function(grunt) {
    var requireJsModulesFront = [];
    grunt.file.expand({cwd: './frontend/static/js/'}, '**/*.js').forEach(function (file) {
        if (file !== 'opt.js' || file !== 'config.js') {
            requireJsModulesFront.push(file.replace(/\.js$/, ''));
        }
    });

    var requireJsModulesBack = [];
    grunt.file.expand({cwd: './backend/static/js/'}, '**/*.js').forEach(function (file) {
       if (file !== 'opt.js' || file !== 'config.js') {
           requireJsModulesBack.push(file.repalce(/\.js$/, ''));
       }
    });

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        less: {
            options: {
                banner: '/* Less 生成的CSS，请勿直接修改 */\n'
            },
            production: {
                files: [
                    {
                        expand: true,
                        cwd: './frontend/static/less',
                        src: ['*.less', '**/*.less', '!code/*.less', '!spec/idea/*.less'],
                        dest: './frontend/temp/less2css/'
                    },
                    {
                        expand: true,
                        cwd: './backend/static/less',
                        src: ['*.less', '**/*.less', '!code/*.less', '!spec/idea/*.less'],
                        dest: './backend/temp/less2css/'
                    }
                ]
            }
        },
        cssmin: {
            combine: {
                files: [
                    {
                        './frontend/web/static/css/all.css': [
                            './frontend/static/css/*.css',
                            './frontend/temp/less2css/*.less',
                            './frontend/static/css/**/*.css',
                            './frontend/temp/less2css/**/*.less'
                        ]
                    },
                    {
                        '/backend/web/static/css/all.css': [
                            './backend/static/css/*.css',
                            './backend/temp/less2css/*.less',
                            './backend/static/css/**/*.css',
                            './backend/temp/less2css/**/*.less'
                        ]
                    }
                ]
            }
        },

        uglify: {
            options: {
                banner: '/*! <%= pkg.name %> */\n',
                mangle: {
                    except: ['jQuery', 'Backbone', 'require']
                }
            },

            build: {
                files: [
                    {
                        expand: true,
                        cwd: './frontend/static/js',
                        src: ['config.js', 'opt.js'],
                        dest: './frontend/web/static/js/'
                    },
                    {
                        expand: true,
                        cwd: './backend/static/js',
                        src: ['config.js', 'opt.js'],
                        dest: './backend/web/static/js'
                    }
                ]
            }
        },

        copy: {
            images: {
                files: [
                    {
                        expand: true,
                        cwd: './frontend/static/images',
                        src: ['**'],
                        dest: './frontend/web/static/images/'
                    },
                    {
                        expand: true,
                        cwd: './backend/static/images',
                        src: ['**'],
                        dest: './backend/web/static/images/'
                    }
                ]
            },
            js: {
                files: [
                    {
                        expand: true,
                        cwd: './frontend/static/js',
                        src: ['*.min.js', '**/*.min.js', 'libs/**'],
                        dest: './frontend/web/static/js/'
                    },
                    {
                        expand: true,
                        cwd: './backend/static/js',
                        src: ['*.min.js', '**/*.min.js', 'libs/**'],
                        dest: './backend/web/static/js/'
                    },
                ]
            },
            css: {
                files: [
                ]
            }
        },

        clean: {
            temp: [
                './frontend/temp',
                './backend/temp'
            ],
            staticFile: [
                './frontend/web/static/**',
                './backend/web/static/**'
            ]
        },
        //eslrev: {
        //    //admin: {
        //    //    options: {
        //    //        map_tpl: './static/js/config.js.tpl',
        //    //        map_realpath: false,
        //    //        prefix: 'admin/ng/'
        //    //    },
        //    //    files: [
        //    //        {
        //    //            cwd: './protected/modules/admin/static/js/angular/',
        //    //            src: ['**/*.js'],
        //    //            dest: './protected/static/js/config.js'
        //    //        }
        //    //    ]
        //    //}
        //},
        requirejs: {
            options: {
                paths: { // 剔除部分无需打包的第三方组件
                    'libs': 'empty:'
                }
            },
            frontend: {
                // overwrites the default config above
                options: {
                    mainConfigFile: './frontend/static/js/config.js',
                    include: requireJsModulesFront,
                    removeCombined: false,
                    out: './frontend/static/js/opt.js',
                    optimize: 'none'
                }
            },
            backend: {
                options: {
                    mainConfigFile: './frontend/static/js/config.js',
                    include: requireJsModulesBack,
                    removeCombined: false,
                    out: './backend/static/js/opt.js',
                    optimize: 'none'
                }
            }

        }
    });

    grunt.loadNpmTasks('grunt-contrib-cssmin');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-copy');

    //grunt.loadNpmTasks('grunt-esl-config-rev');

    grunt.loadNpmTasks('grunt-contrib-requirejs');

    grunt.registerTask('default', ['clean', 'requirejs', 'uglify', 'less', 'cssmin', 'copy', 'clean:temp']);

    grunt.registerTask('js', ['uglify']);
    grunt.registerTask('require', ['requirejs']);
    grunt.registerTask('css', ['less', 'cssmin']);
    grunt.registerTask('img', ['copy']);

    grunt.registerTask('cleanTemp', ['clean']);
};

module.exports = function (grunt) {
  grunt.initConfig({
    // Watch task config
    watch: {
        styles: {
            files: "SCSS/*.scss",
            tasks: ['sass', 'postcss'],
        },
        javascript: {
            files: ["js/*.js", "!js/*.min.js"],
            tasks: ['uglify'],
        },
    },
    sass: {
        dist: {
            options: {
                style: 'compressed'
            },
            files: {
                "orawellness.css" : "SCSS/orawellness.scss",
                "orawellness-login.css" : "SCSS/orawellness-login.scss",
                "wp_knowledgebase/kbe_style.css" : "SCSS/KBE-styles.scss"
            }
        }
    },
    postcss: {
        options: {
            map: {
                inline: false,
            },

            processors: [
                require('pixrem')(), // add fallbacks for rem units
                require('autoprefixer')({browsers: 'last 2 versions'}), // add vendor prefixes
                require('cssnano')() // minify the result
            ]
        },
        dist: {
            src: 'orawellness.css',
            src: 'orawellness-login.css',
            src: 'wp_knowledgebase/kbe_style.css',
        }
    },
    uglify: {
        custom: {
            files: {
                'js/navigation.min.js': ['js/navigation.js'],
                'js/floating-cta.min.js': ['js/floating-cta.js'],
            },
        },
    },
    browserSync: {
        dev: {
            bsFiles: {
                src : ['**/*.css', '**/*.php', '**/*.js', '!node_modules'],
            },
            options: {
                watchTask: true,
                proxy: "http://orawellness.dev",
            },
        },
    },
  });

    grunt.loadNpmTasks('grunt-contrib-sass');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-postcss');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-browser-sync');
    grunt.registerTask('default', [
        'browserSync',
        'watch',
    ]);
};

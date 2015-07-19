module.exports = function(grunt) {
  // time
  require('time-grunt')(grunt);

  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),

    sass: {
      options: {
        // If you can't get source maps to work, run the following command in your terminal:
        // $ sass scss/foundation.scss:css/foundation.css --sourcemap
        // (see this link for details: http://thesassway.com/intermediate/using-source-maps-with-sass )
        sourceMap: true
      },

      dist: {
        options: {
          outputStyle: 'compressed'
        },
        files: {
          'css/foundation.css': 'scss/foundation.scss'
        }
      }
    },

    copy: {
      scripts: {
        expand: true,
        cwd: 'bower_components/foundation/js/vendor/',
        src: ['jquery.js','modernizr.js'],
        flatten: 'true',
        dest: 'js/vendor/'
      },

      iconfonts: {
        expand: true,
        cwd: 'bower_components/fontawesome/',
        src: ['**', '!**/less/**', '!**/css/**', '!bower.json'],
        dest: 'assets/fontawesome/'
      },
    },


      'string-replace': {

        fontawesome: {
          files: {
            'assets/fontawesome/scss/_variables.scss': 'assets/fontawesome/scss/_variables.scss'
          },
          options: {
            replacements: [
              {
                pattern: '../fonts',
                replacement: '../assets/fontawesome/fonts'
              }
            ]
          }
        },
      },

    concat: {
        options: {
          separator: ';',
        },
        dist: {
          src: [

          // Foundation core
          'bower_components/foundation/js/foundation/foundation.js',

          // Pick the components you need in your project
          // 'bower_components/foundation/js/foundation/foundation.abide.js',
          // 'bower_components/foundation/js/foundation/foundation.accordion.js',
          // 'bower_components/foundation/js/foundation/foundation.alert.js',
          // 'bower_components/foundation/js/foundation/foundation.clearing.js',
          'bower_components/foundation/js/foundation/foundation.dropdown.js',
          // 'bower_components/foundation/js/foundation/foundation.equalizer.js',
          // 'bower_components/foundation/js/foundation/foundation.interchange.js',
          // 'bower_components/foundation/js/foundation/foundation.joyride.js',
          // 'bower_components/foundation/js/foundation/foundation.magellan.js',
          // 'bower_components/foundation/js/foundation/foundation.offcanvas.js',
          // 'bower_components/foundation/js/foundation/foundation.orbit.js',
          // 'bower_components/foundation/js/foundation/foundation.reveal.js',
          // 'bower_components/foundation/js/foundation/foundation.slider.js',
          // 'bower_components/foundation/js/foundation/foundation.tab.js',
          // 'bower_components/foundation/js/foundation/foundation.tooltip.js',
          'bower_components/foundation/js/foundation/foundation.topbar.js',

          // Include your own custom scripts (located in the custom folder)
          'js/custom/*.js'


          ],
          // Finally, concatinate all the files above into one single file
          dest: 'js/foundation.js',
        },
      },

    uglify: {
      dist: {
        files: {
          // Shrink the file size by removing spaces
          'js/foundation.js': ['js/foundation.js']
        }
      }
    },

    watch: {
      grunt: { files: ['Gruntfile.js'] },

      sass: {
        files: 'scss/**/*.scss',
        tasks: ['sass'],
        options: {
              livereload:true,
            }
      },

      js: {
        files: 'js/custom/**/*.js',
        tasks: ['concat', 'uglify'],
        options: {
          livereload:true,
        }
      },

       all: {
        files: '**/*.php',
        options: {
            livereload:true,
        }
      }
    },
    exec: {
          get_grunt_sitemap: {
            command: 'curl --silent --output sitemap.json http://footprintslocal:8888/shop/?show_sitemap'
          }
        },

    uncss: {
          dist: {
            options: {
              ignore       : [/expanded/,/js/,/wp-/,/align/,/admin-bar/],
              stylesheets  : ['wp-content/themes/start/css/foundation.css'],
              ignoreSheets : [/fonts.googleapis/],
              urls         : [], //Overwritten in load_sitemap_and_uncss task
            },
            files: {
              'wp-content/themes/start/css/clean.css': ['**/*.php']
            }
          }
        },
  });

  grunt.loadNpmTasks('grunt-sass');
  grunt.loadNpmTasks('grunt-contrib-watch');
  grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-string-replace');
  grunt.loadNpmTasks('grunt-uncss');
  grunt.loadNpmTasks('grunt-exec');

  grunt.registerTask('build', ['copy', 'string-replace:fontawesome', 'sass', 'concat', 'uglify']);
  grunt.registerTask('default', ['watch']);

  grunt.registerTask('load_sitemap_json', function() {
  var sitemap_urls = grunt.file.readJSON('./sitemap.json');
  grunt.config.set('uncss.dist.options.urls', sitemap_urls);
  });

  grunt.registerTask('deploy_build',
  ['exec:get_grunt_sitemap','load_sitemap_json','uncss:dist','sass:dist']);


};

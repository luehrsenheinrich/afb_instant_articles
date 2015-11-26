module.exports = function(grunt) {
  require('jit-grunt')(grunt);
  grunt.template.addDelimiters('underscoresaving', '<##', '##>');
  grunt.template.setDelimiters('underscoresaving');
  grunt.initConfig({

  	// Define variables
    pkg:     grunt.file.readJSON("package.json"),

	// LESS / CSS

	// Compile Less
	// Compile the less files
    less: {
      development: {
        options: {
          compress: true,
          yuicompress: true,
          optimization: 2
        },
        files: {
          "build/css/admin.css": "build/less/admin.less" // destination file and source file
        }
      }
    },


    autoprefixer: {
		options: {
			// Task-specific options go here.
		},
		style: {
            src: 'build/css/admin.css',
			dest: 'build/css/admin.css'
   		}
	},

    // JAVASCRIPT

    // JS HINT
    // How's our code quality
    jshint: {
	    options: {
			reporter: require('jshint-stylish'),
			force: true,
	    },
    	all: ['build/js/**/*.js', '!build/js/**/*.min.js', '!build/js/bootstrap/**/*.js', '!build/js/vendor/**/*.js']
  	},

    // Concat
    // Join together the needed files.
	concat_in_order: {
		main: {
			files: {
				'build/js/afb_ia.min.js': ['build/js/afb_ia.js']
			},
			options: {
			    extractRequired: function(filepath, filecontent) {
				    var path = require('path');

			        var workingdir = path.normalize(filepath).split(path.sep);
			        workingdir.pop();

			        var deps = this.getMatches(/@depend\s"(.*\.js)"/g, filecontent);
			        deps.forEach(function(dep, i) {
			            var dependency = workingdir.concat([dep]);
			            deps[i] = path.join.apply(null, dependency);
			        });
			        return deps;
			    },
			    extractDeclared: function(filepath) {
			        return [filepath];
			    },
			    onlyConcatRequiredFiles: true
			}
		}
	},

	// Uglify
	// We minify the files, we just concatenated
	uglify: {
	    mstartup: {
	      options: {
	      },
	      files: {
			'build/js/afb_ia.min.js': ['build/js/afb_ia.min.js']
	      }
	    }
	},

	// WATCHER / SERVER

    // Watch
    watch: {
	    js: {
		    files: ['build/js/**/*.js', '!build/js/**/*.min.js'],
		    tasks: ['deploy'],
			options: {
				livereload: true
			},
	    },
		less: {
			files: ['build/css/**/*.less'], // which files to watch
			tasks: ['deploy'],
			options: {
				// livereload: true
			},
		},
		css: {
			files: ['build/**/*.css', 'build/*.css', ],
			tasks: [],
			options: {
				livereload: true
			}
		},
		livereload: {
			files: ['build/js/*.min.js', 'build/**/*.php', 'build/**/*.html', 'build/**/*.txt'], // Watch all files
			tasks: ['deploy'],
			options: {
				livereload: true
			}
		},
    },

    // Deployment Strategies

    // Copy the files to the target destination
    copy: {
	    options : {
          processContent: function (content, srcpath) {
			grunt.template.addDelimiters('custom-delimiters', '<##', '##>');
            return grunt.template.process(content, {delimiters: 'custom-delimiters'});
          }
	    },
	    test_plugin: {expand: true, cwd: 'build', src: ['**/*.php', '**/*.min.js', '**/*.css', '**/*.txt','**/*.svg', '!node_modules', '!node_modules/**/*'], dest: 'trunk/', filter: 'isFile'}
    },

    // Clean out folders
    clean: {
		options: { force: true },
		build: {
			expand: true,
			force: true,
		  	cwd: "trunk/",
			src: ['**/*'],
		},
	}
  });

  grunt.registerTask( 'handle_css', ['less', 'autoprefixer', 'uglify'] );
  grunt.registerTask( 'handle_js', ['concat_in_order', 'uglify'] );
  grunt.registerTask( 'dev_js', ['concat_in_order'] );
  grunt.registerTask( 'deploy', ['clean', 'copy'] );

};


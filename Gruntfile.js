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

	// Lint
	// The PHP Linter for code quality
	phplint: {
		dev: ['build/**/*.php'],
	},

	// WATCHER / SERVER

    // Watch
    watch: {
	    js: {
		    files: ['build/js/**/*.js', '!build/js/**/*.min.js'],
		    tasks: ['dev-deploy'],
			options: {
				livereload: true
			},
	    },
		less: {
			files: ['build/css/**/*.less'], // which files to watch
			tasks: ['dev-deploy'],
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
			tasks: ['dev-deploy'],
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
	    build: {expand: true, cwd: 'build', src: ['**/*.php', '**/*.min.js', '**/*.css', '**/*.txt','**/*.svg','**/*.po','**/*.pot','**/*.mo', '!node_modules', '!node_modules/**/*'], dest: 'trunk/', filter: 'isFile'},
	    release: {expand: true, cwd: 'build', src: ['**/*.php', '**/*.min.js', '**/*.css', '**/*.txt','**/*.svg','**/*.po','**/*.pot','**/*.mo', '!node_modules', '!node_modules/**/*'], dest: 'tags/<%= pkg.version %>/', filter: 'isFile'},
	    zip: {expand: true, cwd: 'build', src: ['**/*.php', '**/*.min.js', '**/*.css', '**/*.txt','**/*.svg','**/*.po','**/*.pot','**/*.mo', '!node_modules', '!node_modules/**/*'], dest: '<%= pkg.slug %>/', filter: 'isFile'},
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
		release: {
			expand: true,
			force: true,
		  	cwd: 'tags/<%= pkg.version %>/',
			src: ['**/*'],
		},
		zip: {
			expand: true,
			force: true,
		  	cwd: '<%= pkg.slug %>/',
			src: ['**/*'],
		}
	},

	// Create a ZIP file of the current trunk
	compress: {
	  main: {
	    options: {
	      archive: 'archives/<%= pkg.slug %>.<%= pkg.version %>.zip'
	    },
	    files: [
	      {src: ['**'], cwd: 'trunk', expand: true, dest: '<%= pkg.slug %>'}, // includes files in path
	    ]
	  }
	}
  });

  // These tasks are not needed at the moment, as we do not have any css or js files (yet).
  grunt.registerTask( 'handle_css', ['less', 'autoprefixer', 'uglify'] );
  grunt.registerTask( 'handle_js', ['concat_in_order', 'uglify'] );

  // Deployment strategies. The dev-deploy runs with the watcher and performs quicker. The deploy performs a clean of the trunk folder and a clean copy of the needed files.
  grunt.registerTask( 'deploy', ['phplint', 'clean:build', 'copy:build'] );
  grunt.registerTask( 'dev-deploy', ['phplint', 'newer:copy:build'] );

  // The release task adds a new tag in the release folder.
  grunt.registerTask( 'zip', ['copy:zip', 'compress', 'clean:zip'] );
  grunt.registerTask( 'release', ['deploy', 'clean:release', 'copy:release', 'zip'] );


};


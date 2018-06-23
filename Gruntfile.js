const webpackConfig = require('./webpack.config');
const gruntNewerLess = require('grunt-newer-less');

module.exports = function(grunt) {
  require('jit-grunt')(grunt);

  grunt.initConfig({

  	// Define variables
	pkg:	 grunt.file.readJSON("package.json"),
	pkgLck:	 grunt.file.readJSON("package-lock.json"),

	// LESS / CSS

	// Compile Less
	// Compile the less files
	less: {
	  development: {
		options: {
		  optimization: 2
		},
		files: {
		  "build/style.css": "build/less/style.less", // destination file and source file
		  "build/admin/admin.css": "build/admin/less/admin.less", // destination file and source file

		  "build/blocks/blocks-editor.css": "build/blocks/blocks-editor.less",
		  "build/blocks/blocks.css": "build/blocks/blocks.less",

		  "build/blocks-premium/blocks-editor.css": "build/blocks-premium/blocks-editor.less",
		  "build/blocks-premium/blocks.css": "build/blocks-premium/blocks.less",

		  "build/css/font-awesome.css": "build/less/font-awesome.less"
		}
	  },
	  fonts: {
		  files: {
			  "build/webfonts.css": "build/less/webfonts.less",
		  }
	  }
	},

	postcss: {
		fonts: {
			src: 'build/webfonts.css',
			options: {
			  map: false, // inline sourcemaps
			  processors: [
				require('postcss-base64')({
					extensions: ['.woff'],
					excludeAtFontFace: false,
					root: 'build',
				}), // add vendor prefixes
			  ]
			},
		},
		autoprefix: {
			options: {
			  map: false, // inline sourcemaps
			  processors: [
				require('autoprefixer')({browsers: 'last 2 versions'}), // add vendor prefixes
			  ]
			},
			files: {
				"build/style.css": "build/style.css",
				"build/admin/admin.css": "build/admin/admin.css",
				"build/blocks/blocks-editor.css": "build/blocks/blocks-editor.css",
				"build/blocks/blocks.css": "build/blocks/blocks.css",
				"build/blocks-premium/blocks-editor.css": "build/blocks-premium/blocks-editor.css",
				"build/blocks-premium/blocks.css": "build/blocks-premium/blocks.css",
				"build/css/font-awesome.css": "build/css/font-awesome.css"
			}
		},
		minify: {
			options: {
				map: false,
				processors: [
					require('cssnano')({}),
				]
			},
			files: {
				"build/style.css": "build/style.css",
				"build/admin/admin.css": "build/admin/admin.css",
				"build/blocks/blocks-editor.css": "build/blocks/blocks-editor.css",
				"build/blocks/blocks.css": "build/blocks/blocks.css",
				"build/blocks-premium/blocks-editor.css": "build/blocks-premium/blocks-editor.css",
				"build/blocks-premium/blocks.css": "build/blocks-premium/blocks.css",
				"build/css/font-awesome.css": "build/css/font-awesome.css"
			}
		}
	},

	// JAVASCRIPT

	webpack: {
      prod: webpackConfig,
    },

	// PHP Coding Standards
	// Check the PHP Code for the needed PSR2 rule set
	phpcs: {
		application: {
			src: ['build/**/*.php', 'build/theme_engine/**/*', '!build/freemius/**/*']
		},
		options: {
			bin: 'wpcs/vendor/bin/phpcs',
			standard: 'WordPress-LH',
			warningSeverity: 0
		}
	},

	// WATCHER / SERVER

	// Watch
	watch: {
		js: {
			files: ['build/**/*.js', '!build/**/*.min.js', '!build/**/*.bundle.js'],
			tasks: ['dev-deploy'],
			options: {
				livereload: true
			},
		},
		less: {
			files: ['build/**/*.less'], // which files to watch
			tasks: ['deploy_css'],
			options: {
				//livereload: true
			},
		},
		css: {
			files: ['build/**/*.css', 'build/*.css', ],
			tasks: [],
			options: {
				livereload: true
			}
		},
		php: {
			files: ['build/**/*.php'], // which files to watch
			tasks: ['deploy_php'],
			options: {
				livereload: true
			},
		},
		livereload: {
			files: ['build/**/*.html', 'build/**/*.txt'], // Watch all files
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
		  process: function (content, srcpath) {
			if(typeof(content) === "object"){
			 	return content;
			};
			grunt.template.addDelimiters('custom-delimiters', '<##', '##>');
			return grunt.template.process(content, {delimiters: 'custom-delimiters'});
		  },
		},
		build_css:  {expand: true, cwd: 'build', src: ['**/*.css'], dest: 'trunk/', filter: 'isFile'},
		build_php:  {expand: true, cwd: 'build', src: ['**/*.php'], dest: 'trunk/', filter: 'isFile'},
		build_freemius:  {expand: true, cwd: 'build', src: ['freemius/**/*'], dest: 'trunk/', filter: 'isFile'},
		build: {expand: true, cwd: 'build', src: ['**/*.min.js', '**/*.css', '**/*.txt','**/*.svg','**/*.po','**/*.pot', '**/*.tmpl.html'], dest: 'trunk/', filter: 'isFile'},
		build_stream: {expand: true, options: { encoding: null }, cwd: 'build', src: ['**/*.mo', 'img/**/*', 'freemius/assets/img/*', 'screenshot.png'], dest: 'trunk/', filter: 'isFile'},
		colors_less:  {expand: true, cwd: 'build/less/mixins', src: ['theme.less'], dest: 'trunk/theme_engine', filter: 'isFile'},

		build_fontawesome: {
			expand: true,
			options: { encoding: null },
			cwd: 'node_modules/font-awesome/fonts',
			src: ['*'],
			dest: 'trunk/fonts/font-awesome',
			filter: 'isFile'
		}
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
		  archive: 'update/<%= pkg.slug %>.zip'
		},
		files: [
		  {src: ['**'], cwd: 'trunk', expand: true, dest: '<%= pkg.slug %>'}, // includes files in path
		]
	  }
	},

	newer: {
		options: {
			override: gruntNewerLess.overrideLess
		}
	}
  });

  // These tasks are not needed at the moment, as we do not have any css or js files (yet).
  grunt.registerTask( 'handle_css', ['newer:less:development', 'newer:postcss:autoprefix'] );
  grunt.registerTask( 'handle_js', ['webpack'] );
  grunt.registerTask( 'handle_php', ['newer:phpcs'] );
  grunt.registerTask( 'handle_fonts', ['less:fonts', 'postcss:fonts'] );

  // copy admin stuff
  grunt.registerTask( 'handle_admin_copy', ['copy:admin_fonts', 'copy:admin_tmpl', 'copy:admin_assets'] );

  // Deployment strategies. The dev-deploy runs with the watcher and performs quicker. The deploy performs a clean of the trunk folder and a clean copy of the needed files.
  grunt.registerTask( 'deploy_css', ['handle_css', 'newer:copy:build_css', 'newer:copy:colors_less'] );
  grunt.registerTask( 'deploy_php', ['handle_php', 'newer:copy:build_php', 'newer:copy:colors_less'] );

  grunt.registerTask( 'deploy', ['handle_js', 'handle_css', 'postcss:minify', 'handle_fonts', 'handle_php', 'clean:build', 'copy:build', 'copy:build_css', 'copy:build_php', 'copy:build_stream', 'copy:build_fontawesome', 'copy:build_freemius', 'copy:colors_less'] );

  grunt.registerTask( 'dev-deploy', ['handle_js', 'handle_css', 'newer:copy:build', 'newer:copy:build_stream'] );

  // The release task adds a new tag in the release folder.
  grunt.registerTask( 'release', ['deploy', 'compress'] );


};

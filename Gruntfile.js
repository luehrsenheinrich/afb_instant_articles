const webpackConfig = require( './webpack.config' );

const postCssPresetEnvOptions = {
	stage: 3,
	features:	{
		'custom-media-queries': {
			preserve: false,
		},
		'custom-properties': {
			preserve: true,
		},
		'nesting-rules': true,
	},
};

module.exports = function( grunt ) {
	// measures the time each task takes
	// require( 'time-grunt' )( grunt );

	// Get the needed modules just in time
	require( 'jit-grunt' )( grunt, {
		// Define private modules
		postcss: '@lodder/grunt-postcss',
	} );

	// Define the initial config for grunt
	grunt.initConfig( {

		// Define variables
		pkg: grunt.file.readJSON( 'package.json' ),

		// OPTIMIZE CSS - Take the created CSS files and run some plugins on it
		postcss: {
			default: {
				options: {
					map: false,
					processors: [
						require( 'postcss-import' )(),
						require( 'postcss-normalize' )(),
						require( 'postcss-preset-env' )( postCssPresetEnvOptions ),
					],
				},
				files: [
					{
						expand: true,
						cwd: 'build/css',
						src: '*.css',
						dest: 'trunk/css/',
					},
					{
						expand: true,
						cwd: 'build/blocks',
						src: 'blocks.css',
						dest: 'trunk/css/',
					},
					{
						expand: true,
						cwd: 'build/blocks',
						src: 'blocks-editor.css',
						dest: 'trunk/css/',
					},
				],
			},
			minify: {
				options: {
					map: false,
					processors: [
						require( 'cssnano' )(),
					],
				},
				files: [
					{
						expand: true,
						cwd: 'trunk/css',
						src: [ '*.css', '!*.min.css' ],
						dest: 'trunk/css',
						ext: '.min.css',
					},
				],
			},
		},

		// LINT CSS - Lint the css we wrote
		stylelint: {
			all: [ 'build/**/*.css', '!build/puc/**/*.css' ],
		},

		// PROCESS JS - Use webpack to process the needed js files
		webpack: {
			prod: webpackConfig,
		},

		// // SHELL - Run needed shell commands
		shell: {
			lintPHP: 'composer run lint',
		},

		// // ESLINT - Make sure our JS follows coding standards
		eslint: {
			target: [ 'build/js/**/*.js' ],
		},

		// COPY FILES - Copy needed files from build to trunk
		copy: {
			options: {
				process( content ) {
					if ( typeof content !== 'string' ) {
						return content;
					}
					return grunt.template.process( content );
				},
			},
			build: { expand: true, cwd: 'build', src: [ 'style.css', '**/*.txt', '**/*.svg', '**/*.po', '**/*.pot', '**/*.tmpl.html', '**/*.php' ], dest: 'trunk/', filter: 'isFile' },
			build_css: { expand: true, cwd: 'dist/css', src: [ '*.min.css' ], dest: 'trunk/css/', filter: 'isFile' },
			build_stream: { expand: true, options: { encoding: null }, cwd: 'build', src: [ '**/*.mo', 'img/**/*', 'screenshot.png', 'fonts/**/*' ], dest: 'trunk/', filter: 'isFile' },
		},

		// CLEAN FOLDERS - Before we compile freshly, we want to delete old folder contents
		clean: {
			options: { force: true },
			dist_css: {
				expand: true,
				force: true,
				cwd: 'dist/css/',
				src: [ '**/*' ],
			},
			trunk: {
				expand: true,
				force: true,
				cwd: 'trunk/',
				src: [ '**/*' ],
			},
			zip: {
				expand: true,
				force: true,
				cwd: '<%= pkg.slug %>/',
				src: [ '**/*' ],
			},
		},

		// COMPRESS - Create a zip file from a new trunk
		compress: {
			main: {
				options: {
					archive: 'update/<%= pkg.slug %>.zip',
				},
				files: [
					{ src: [ '**' ], cwd: 'trunk', expand: true, dest: '<%= pkg.slug %>' },
				],
			},
		},

		// WATCHER - Watch for changes in files and process those when a change is detected
		watch: {
			js: {
				files: [ 'build/**/*.js', 'build/**/*.json', '!build/**/*.min.js', '!build/**/*.bundle.js' ],
				tasks: [ 'handle_js' ],
				options: {
				},
			},
			css: {
				files: [ 'build/**/*.css' ], // which files to watch
				tasks: [ 'newer_handle_css' ],
				options: {
				},
			},
			php: {
				files: [ 'build/**/*.php' ], // which files to watch
				tasks: [ 'dev_deploy' ],
				options: {
				},
			},
			static: {
				files: [ 'build/**/*.html', 'build/**/*.txt' ], // Watch all files
				tasks: [ 'dev_deploy' ],
				options: {
				},
			},
			livereload: {
				// Here we watch the files the sass task will compile to
				// These files are sent to the live reload server after less compiles to them
				options: { livereload: true },
				files: [ 'trunk/**/*' ],
			},
		},
	} );

	// Handle certain file groups
	grunt.registerTask( 'newer_handle_css', [ 'postcss:default', 'postcss:minify' ] );
	grunt.registerTask( 'handle_css', [ 'clean:dist_css', 'postcss:default', 'postcss:minify' ] );

	grunt.registerTask( 'newer_handle_js', [ 'webpack' ] );
	grunt.registerTask( 'handle_js', [ 'webpack' ] );

	// // Deployment strategies
	grunt.registerTask( 'dev_deploy', [ 'newer_handle_css', 'newer_handle_js', 'newer:copy:build', 'newer:copy:build_css', 'newer:copy:build_stream' ] );
	grunt.registerTask( 'deploy', [ 'clean:trunk', 'handle_css', 'handle_js', 'copy:build', 'copy:build_css', 'copy:build_stream' ] );

	// // Linting
	grunt.registerTask( 'lint', [ 'shell:lintPHP', 'eslint', 'stylelint' ] );

	// // Releasing
	grunt.registerTask( 'release', [ 'lint', 'deploy', 'compress' ] );
};

const path = require( 'path' );
const webpack = require( 'webpack' );
const UglifyJsPlugin = require( 'uglifyjs-webpack-plugin' );
const DependencyExtractionWebpackPlugin = require( '@wordpress/dependency-extraction-webpack-plugin' );

module.exports = {
	entry: {
		'js/script.min': path.resolve( __dirname, './build/js/script.js' ),
		'js/blocks.min': path.resolve( __dirname, './build/blocks/blocks.js' ),
		'js/blocks-editor.min': path.resolve( __dirname, './build/blocks/blocks-frontend.js' ),
		'js/blocks-helper.min': path.resolve( __dirname, './build/blocks-helper/blocks-helper.js' ),
	},
	mode: 'none',
	output: {
		path: path.resolve( __dirname, './trunk' ),
		filename: '[name].js',
	},
	plugins: [
		new webpack.ProvidePlugin( {
			jQuery: 'jquery',
			$: 'jquery',
			wp: 'wp',

		} ),
		new UglifyJsPlugin( {
			include: /\.min\.js$/,
		} ),
		new DependencyExtractionWebpackPlugin(),
	],
	externals: {
		jquery: 'jQuery',
		wp: 'wp',
		react: 'React',
		'react-dom': 'ReactDOM',
	},
	resolve: {
		modules: [
			'node_modules',
		],
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				loader: 'babel-loader',
				include: path.resolve( __dirname, 'build' ),
			},
		],
	},
};

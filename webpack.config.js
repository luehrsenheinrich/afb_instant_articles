const path = require('path');
const webpack = require('webpack');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')

module.exports = {
  entry: {
    'js/script.min': path.resolve(__dirname, './build/js/script.js'),
    'js/script.bundle': path.resolve(__dirname, './build/js/script.js'),

    'blocks/blocks.min': path.resolve(__dirname, './build/blocks/blocks.js'),
    'blocks/blocks.bundle': path.resolve(__dirname, './build/blocks/blocks.js'),

    'admin/customizer.min': path.resolve(__dirname, './build/admin/customizer.js'),
    'admin/customizer.bundle': path.resolve(__dirname, './build/admin/customizer.js'),

    'blocks-premium/blocks.min': path.resolve(__dirname, './build/blocks-premium/blocks.js'),
  },
  mode: 'none',
  output: {
    path: path.resolve(__dirname, './build'),
    filename: '[name].js'
  },
  plugins: [
	new webpack.ProvidePlugin({
		jQuery: "jquery",
		wp: 'wp'
	}),
    new UglifyJsPlugin({
		include: /\.min\.js$/,
    })
  ],
  externals: {
	jquery: 'jQuery',
	wp: 'wp'
  },
  resolve: {
		modules: [
			'node_modules',
		],
	},
	module: {
	  rules: [
	    { test: /\.js$/, exclude: /node_modules/, loader: "babel-loader", include: path.resolve(__dirname, "build") }
	  ]
	}
};

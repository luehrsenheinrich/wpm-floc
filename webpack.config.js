const path = require('path');
const webpack = require('webpack');
const TerserPlugin = require('terser-webpack-plugin');
const DependencyExtractionWebpackPlugin = require('@wordpress/dependency-extraction-webpack-plugin');
const glob = require('glob');

module.exports = {
	entry: glob.sync('./build/js/*.js').reduce(function (obj, el) {
		obj[path.parse(el).name + '.min'] = el;
		obj[path.parse(el).name + '.bundle'] = el;
		return obj;
	}, {}),
	mode: 'none',
	output: {
		path: path.resolve(__dirname, './trunk/js'),
		filename: '[name].js',
	},
	plugins: [
		new webpack.ProvidePlugin({
			jQuery: 'jquery',
			$: 'jquery',
			wp: 'wp',
		}),
		new TerserPlugin({
			include: /\.min\.js$/,
		}),
		new DependencyExtractionWebpackPlugin(),
	],
	externals: {
		jquery: 'jQuery',
		wp: 'wp',
		react: 'React',
		'react-dom': 'ReactDOM',
	},
	resolve: {
		modules: ['node_modules'],
	},
	module: {
		rules: [
			{
				test: /\.js$/,
				exclude: /node_modules/,
				loader: 'babel-loader',
				include: path.resolve(__dirname, 'build'),
			},
		],
	},
};

const path = require('path');
const MiniCssExtractPlugin = require('mini-css-extract-plugin');
const WebpackNotifierPlugin = require('webpack-notifier');

module.exports = {
	// mode: 'development',
	mode: 'production',
	entry: {
		Admin: './src/js/admin/index.js',
		Lodge: './src/js/lodge/index.js',
		App: './src/js/index.js'
	},
	stats: {
		all: false,
		assets: true,
		modules: true,
		maxModules: 0,
		errors: true,
		warnings: true,
		moduleTrace: true,
		errorDetails: true
	},
	devtool: 'inline-source-map',
	module: {
		rules: [{
			test: /\.s?css$/i,
			use: [{ loader: MiniCssExtractPlugin.loader, options: { publicPath: '../' } }, { loader: 'css-loader', options: { url: false } },  'sass-loader' ],
		},{
			test: /\.(js|jsx)$/,
			exclude: /node_modules/,
			use: {
				loader: "babel-loader"
			}
		},{
			test: /\.(woff|woff2|eot|ttf|otf|png|svg|jpg|gif)$/,
			loader: require.resolve("file-loader") + "?name=../[path][name].[ext]"
		}]
	},
	// optimization: { minimizer: [ new TerserJSPlugin(), new OptimizeCSSAssetsPlugin(), new CleanWebpackPlugin() ] },
	plugins: [ new WebpackNotifierPlugin({alwaysNotify: true}), new MiniCssExtractPlugin({filename: 'css/[name].css'}) ],
	externals:{
		jquery: '$'
	},
	resolve: {
		extensions: ['*', '.js', '.jsx'],
		modules: [ path.resolve(__dirname, 'js'), 'node_modules' ]
	},
	watch: true,
	output: {
		filename: 'js/[name].js',
		path: path.resolve(__dirname, 'dist'),
		umdNamedDefine: true,
		library: '[name]',
		libraryTarget:'umd',
		libraryExport: 'default'
	}
}
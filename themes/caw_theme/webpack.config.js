const path = require("path");

const config = {
  isProd: process.env.NODE_ENV === "production",
  hmrEnabled: process.env.NODE_ENV !== "production" && !process.env.NO_HMR,
  distFolder: path.resolve(__dirname, "./dist"),
  publicPath: "/assets",
  wdsPort: 3001,
};

const Webpack = require("webpack");
const AssetsWebpackPlugin = require('assets-webpack-plugin');
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");

const glob = require('glob')

const componentStyles = glob.sync('./lib/components/**/*.scss').reduce((acc, path) => {
  const entry = path.replace('.scss', '').replace('./lib/', '');
  acc[`css/${entry}`] = path
  return acc
}, {});

const styleSheets = glob.sync('./lib/scss/**/*.scss').reduce((acc, file) => {
  if (file.indexOf('/_') > 0) {
    return acc;
  }
  const entry =  path.basename(file).split('.').slice(0, -1).join('.');
  acc[`css/${entry}`] = file
  return acc
}, {});

var webpackConfig = {
  entry: {...componentStyles, ...styleSheets },
  output: {
    path: config.distFolder,
    filename: '[name].js',
    publicPath: config.publicPath,
    clean: true
  },
  mode: config.isProd ? "production" : "development",
  module: {
    rules: [
      {
        test: /\.m?js$/,
        exclude: /(node_modules)/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        },
      },
      {
        test: /\.(sa|sc|c)ss$/,
        use: [
          config.isProd ? { loader: MiniCssExtractPlugin.loader } : 'style-loader',
          'css-loader',
          'postcss-loader',
          'sass-loader'
        ],
      }
    ]
  },
  plugins: [
    new AssetsWebpackPlugin({path: config.distFolder}),
    new MiniCssExtractPlugin({
      filename: '[name].css',
    }),
  ],
  optimization: {
    minimizer: [
      new OptimizeCSSAssetsPlugin(),
    ]
  }

};

if (config.hmrEnabled) {
  webpackConfig.plugins.push(new Webpack.HotModuleReplacementPlugin());
}
module.exports = webpackConfig;

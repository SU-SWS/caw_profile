/**
 * Webpack Configuration File
 * @type {[type]}
 */

// /////////////////////////////////////////////////////////////////////////////
// Requires / Dependencies /////////////////////////////////////////////////////
// /////////////////////////////////////////////////////////////////////////////

const path = require('path');
const webpack = require('webpack');
const autoprefixer = require('autoprefixer');
const FileManagerPlugin = require('filemanager-webpack-plugin');
const UglifyJsPlugin = require("uglifyjs-webpack-plugin");
const MiniCssExtractPlugin = require("mini-css-extract-plugin");
const OptimizeCSSAssetsPlugin = require("optimize-css-assets-webpack-plugin");
const WebpackAssetsManifest = require("webpack-assets-manifest");
const ExtraWatchWebpackPlugin = require("extra-watch-webpack-plugin");
const FixStyleOnlyEntriesPlugin = require("webpack-fix-style-only-entries");
const glob = require('glob')

// /////////////////////////////////////////////////////////////////////////////
// Paths ///////////////////////////////////////////////////////////////////////
// /////////////////////////////////////////////////////////////////////////////

const npmPackage = 'node_modules';
const srcDir = path.resolve(__dirname, "lib");
const distDir = path.resolve(__dirname, "dist");
const srcSass = path.resolve(__dirname, srcDir, "scss");
const distSass = path.resolve(__dirname, distDir, "css");
const srcJS = path.resolve(__dirname, srcDir, "js");
const distJS = path.resolve(__dirname, distDir, "js");
const srcAssets = path.resolve(__dirname, srcDir, "assets");
const distAssets = path.resolve(__dirname, distDir, "assets");

// /////////////////////////////////////////////////////////////////////////////
// Functions ///////////////////////////////////////////////////////////////////
// /////////////////////////////////////////////////////////////////////////////

// /////////////////////////////////////////////////////////////////////////////
// Config //////////////////////////////////////////////////////////////////////
// /////////////////////////////////////////////////////////////////////////////


const entryPoints = glob.sync('./lib/scss/**/*.scss').reduce((acc, filePath) => {
  const filePathParts = filePath.replace('./lib/scss/', '').split('/');
  let fileName = filePathParts.pop();
  if (fileName.indexOf('_') === 0) {
    return acc;
  }
  if (fileName === 'index.scss') {
    fileName = filePathParts.pop();
  }
  const entry = filePathParts.length >= 1 ? filePathParts.join('/') + '/' + fileName : fileName;
  acc[entry.replace('.scss', '')] = filePath
  return acc
}, {});

// Start configuring webpack.
var webpackConfig = {
  // What am i?
  name: 'stanford_profile_styles',
  // Allows for map files.
  devtool: 'source-map',
  // What build?
  entry: entryPoints,

  // Where put build?
  output: {
    filename: "[name].js",
    path: path.resolve(__dirname, distJS),
  },
  // Relative output paths for css assets.
  resolve: {
    alias: {
      'decanter-assets': path.resolve(npmPackage, 'decanter/core/src/img'),
      'decanter-src': path.resolve(npmPackage, 'decanter/core/src'),
      '@fortawesome': path.resolve(npmPackage, '@fortawesome'),
      'fa-fonts': path.resolve(npmPackage, '@fortawesome/fontawesome-free/webfonts')
    }
  },
  // Additional module rules.
  module: {
    rules: [
      // Drupal behaviors need special handling with webpack.
      // https://www.npmjs.com/package/drupal-behaviors-loader
      {
        test: /\.behavior.js$/,
        exclude: /node_modules/,
        options: {
          enableHmr: false
        },
        loader: 'drupal-behaviors-loader'
      },
      // Good ol' Babel.
      {
        test: /\.js$/,
        loader: 'babel-loader',
        query: {
          presets: ['@babel/preset-env']
        }
      },
      // Apply Plugins to SCSS/SASS files.
      {
        test: /\.s[ac]ss$/,
        use: [
          // Extract loader.
          MiniCssExtractPlugin.loader,
          // CSS Loader. Generate sourceMaps.
          {
            loader: 'css-loader',
            options: {
              sourceMap: true,
              url: true
            }
          },
          // Post CSS. Run autoprefixer plugin.
          {
            loader: 'postcss-loader',
            options: {
              sourceMap: true,
              plugins: () => [
                autoprefixer({ grid: true })
              ]
            }
          },
          // SASS Loader. Add compile paths to include bourbon.
          {
            loader: 'sass-loader',
            options: {
              includePaths: [
                path.resolve(__dirname, npmPackage, "bourbon/core"),
                path.resolve(__dirname, srcSass),
                path.resolve(__dirname, npmPackage)
              ],
              sourceMap: true,
              lineNumbers: true,
              outputStyle: 'nested',
              precision: 10
            }
          }
        ]
      },
      // Apply plugin to font assets.
      {
        test: /\.(woff2?|ttf|otf|eot)$/,
        loader: 'file-loader',
        options: {
          name: "[name].[ext]",
          publicPath: "../assets/fonts",
          outputPath: "../assets/fonts"
        }
      },
      // Apply plugins to image assets.
      {
        test: /\.(png|jpg|gif)$/i,
        use: [
          // A loader for webpack which transforms files into base64 URIs.
          // https://github.com/webpack-contrib/url-loader
          {
            loader: "file-loader",
            options: {
              name: "[name].[ext]",
              publicPath: "../../assets/img",
              outputPath: "../../assets/img"
            }
          }
        ]
      },
      // Apply plugins to svg assets.
      {
        test: /\.(svg)$/i,
        use: [
          {
            loader: "file-loader",
            options: {
              name: "[name].[ext]",
              publicPath: "../../assets/svg",
              outputPath: "../assets/svg"
            }
          }
        ]
      },
    ]
  },
  // Build optimizations.
  optimization: {
    // Uglify the Javascript & and CSS.
    minimizer: [
      // Shrink JS.
      new UglifyJsPlugin({
        cache: true,
        parallel: true,
        sourceMap: true
      }),
      // Shrink CSS.
      new OptimizeCSSAssetsPlugin({})
    ],
  },
  // Plugin configuration.
  plugins: [
    // Remove JS files from render.
    new FixStyleOnlyEntriesPlugin(),
    // Output css files.
    new MiniCssExtractPlugin({
      filename:  "../css/[name].css"
    }),
    // A webpack plugin to manage files before or after the build.
    // https://www.npmjs.com/package/filemanager-webpack-plugin
    new FileManagerPlugin({
      onStart: {
        delete: [distDir]
      }
    })
  ]
};

// Add the configuration.
module.exports = [ webpackConfig ];

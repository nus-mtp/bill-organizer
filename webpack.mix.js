const { mix } = require('laravel-mix')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.webpackConfig({
  plugins: [
    new BrowserSyncPlugin({
      // browse to http://localhost:8000 during development,
      // ./public directory is being served
      files: ['**/*.css', 'resources/views/**/*.php', 'app/**/*.php', 'routes/**/*.php']
    }, {reload: false})
  ]
})

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')

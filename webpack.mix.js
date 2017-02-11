const { mix } = require('laravel-mix')
const path = require('path')
mix.browserSync('localhost:8000')

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

mix.copy('node_modules/semantic-ui-sass/app/assets/images/', 'public/images/')
  .copy('node_modules/semantic-ui-sass/app/assets/fonts/', 'public/fonts/')
  .js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')

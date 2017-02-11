const { mix } = require('laravel-mix')
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

mix.copy('semantic/dist/themes/default/assets/images', 'public/images/')
  .copy('semantic/dist/themes/default/assets/fonts', 'public/fonts/')
  .copy('semantic/dist/semantic.css', 'resources/assets/sass/semantic.css')
  .copy('semantic/dist/semantic.js', 'resources/assets/js/semantic.js')
  .js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')

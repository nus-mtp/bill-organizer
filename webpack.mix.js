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

mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css')
/*   .copy('resources/assets/bower/semantic/dist/semantic.min.css', 'public/css/semantic.min.css')
    .copy('resources/assets/bower/semantic/dist/semantic.min.js', 'public/js/semantic.min.js')
*/

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

/* mix.webpackConfig({
  resolve: {
    alias: {
      semantic_path: path.resolve('resources/semantic/dist/')
    }
  }
}) */

mix.copy('semantic/dist/semantic.css', 'resources/assets/sass/semantic.css')
  .copy('semantic/dist/semantic.js', 'resources/assets/js/semantic.js')
  .js('resources/assets/js/app.js', 'public/js').extract(['jquery', 'lodash', 'axios'])
   .sass('resources/assets/sass/app.scss', 'public/css')

// Full API
// mix.js(src, output);
// mix.extract(vendorLibs);
// mix.sass(src, output);
// mix.less(src, output);
// mix.combine(files, destination);
// mix.copy(from, to);
// mix.minify(file);
// mix.sourceMaps(); // Enable sourcemaps
// mix.version(); // Enable versioning.
// mix.disableNotifications();
// mix.setPublicPath('path/to/public'); <-- Useful for Node apps.
// mix.webpackConfig({}); <-- Override webpack.config.js, without editing the file directly

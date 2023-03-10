const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js(['../src/node_modules/bootstrap/js/dist/tab.js', '../src/node_modules/bootstrap/js/dist/collapse.js'], 'public/js/bootstrap.js')
    .sass('resources/scss/bootstrap.scss', 'public/css/bootstrap.css');

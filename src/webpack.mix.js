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

mix
    .js('resources/js/app.js', 'public/js')
    .copy(['resources/js/site-name-autocomplete.js','resources/js/species-name-autocomplete.js','resources/js/update-dataset.js'], 'public/js/')
    .js(['../src/node_modules/bootstrap/js/src/collapse.js','../src/node_modules/bootstrap/js/src/tab.js'],'public/js/bootstrap.js')
    .copy(['resources/js/BasicMap.js', 'resources/js/Leaflet.MetricGrid.js', 'resources/js/leaflet.wms.js', '../src/node_modules/proj4/dist/proj4.js','../src/node_modules/wicket/wicket.js', '../src/node_modules/brc-atlas-bigr/dist/bigr.min.umd.js', '../src/node_modules/d3/dist/d3.min.js'], 'public/js/')
    .sass('resources/scss/bootstrap.scss', 'public/css/bootstrap.css')
    .copy(['resources/css/app.css','resources/css/shropshire-style.css', 'resources/css/enhancements.css'], 'public/css/')
    .css('../src/node_modules/leaflet/dist/leaflet.css','public/css/leaflet.css');

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
    .copy(['../src/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js','../src/node_modules/bootstrap/dist/js/bootstrap.bundle.min.js.map'],'public/js/')
    .copy(['resources/js/site-name-autocomplete.js','resources/js/species-name-autocomplete.js','resources/js/update-dataset.js'], 'public/js/')
    .combine(['resources/js/BasicMap.js', '../src/node_modules/leaflet/dist/leaflet.js','resources/js/Leaflet.MetricGrid.js', '../src/node_modules/proj4/dist/proj4.js','../src/node_modules/wicket/wicket.js', '../src/node_modules/brc-atlas-bigr/dist/bigr.min.umd.js', '../src/node_modules/d3/dist/d3.min.js'], 'public/js/mapping.js').sourceMaps()
    .copy(['../src/node_modules/leaflet/dist/leaflet.js.map'], 'public/js/')
    .sass('resources/scss/bootstrap.scss', 'public/css/bootstrap.css')
    .copy(['resources/css/app.css','resources/css/shropshire-style.css', 'resources/css/enhancements.css'], 'public/css/')
    .css('../src/node_modules/leaflet/dist/leaflet.css','public/css/leaflet.css');

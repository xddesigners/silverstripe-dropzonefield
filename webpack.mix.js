const mix = require('laravel-mix');
require('laravel-mix-polyfill');

mix.js('client/src/js/ssdropzonefield.js', 'client/dist/js')
  .sass('client/src/styles/ssdropzonefield.scss', 'client/dist/styles')
  .sourceMaps()
  .polyfill({
    enabled: mix.inProduction(),
    useBuiltIns: "usage",
    targets: {"ie": 11}
  });
var elixir = require('laravel-elixir');
var theme = 'playbook';
elixir.config.assetsPath = './';

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Statamic theme. By default, we are compiling the Sass
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
  mix
    .sass(theme + '.scss', 'css/' + theme + '.css')
    .coffee(['./coffee/**/*.coffee'], 'js/compiled/' + theme + '.js')
    .scripts([
      // 'vendor/modernizr.js',
      // 'vendor/jquery-2.2.3.min.js',
      // 'vendor/clipboard.js',
      'compiled/' + theme + '.js'
    ], 
      'js/' + theme + '.js'
    )
});
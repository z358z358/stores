var elixir = require('laravel-elixir');

/*
 |--------------------------------------------------------------------------
 | Elixir Asset Management
 |--------------------------------------------------------------------------
 |
 | Elixir provides a clean, fluent API for defining some basic Gulp tasks
 | for your Laravel application. By default, we are compiling the Less
 | file for our application, as well as publishing vendor resources.
 |
 */

elixir(function(mix) {
    //mix.sass('app.scss', 'resources/css');

    mix.styles([
    	'libs/bootstrap.min.css',
        'libs/font-awesome.min.css',
    	'libs/landing-page.css',
        'my.css',
    ], 'public/css/app.css')

    .scripts([
    	'libs/jquery.js',
    	'libs/bootstrap.min.js',
    ], 'public/js/all.js')

    .styles([
        'libs/select2.css',
    ], 'public/css/select2.css')

    .scripts([
        'libs/select2.js',
        'select2_my.js',
    ], 'public/js/select2.js')

    .version(['public/css/all.css', 'public/js/all.js', 'public/css/select2.css', 'public/js/select2.js'])

    .copy('./resources/css/fonts/**', 'public/build/fonts');
});

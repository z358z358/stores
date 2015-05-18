var elixir = require('laravel-elixir');
require('laravel-elixir-clean');
//require("laravel-elixir-react");

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
elixir.config.sourcemaps = false;

elixir(function(mix) {

    mix

    .clean()

    //.react("react_my.jsx")

    .styles([
    	'libs/bootstrap.css',
        'libs/bootstrap-social.css',
        'libs/font-awesome.css',
    	'libs/landing-page.css',
        'my.css',
    ], 'public/css/all.css', 'resources/assets/css')

    .styles([
        'libs/select2.css',
    ], 'public/css/select2.css', 'resources/assets/css')

    .styles([
        'libs/sb-admin-2.css',
    ], 'public/css/sb-admin-2.css', 'resources/assets/css')

    .scripts([
    	'libs/jquery.js',
    	'libs/bootstrap.js',
        'libs/jquery.timeago.js',
    ], 'public/js/all.js', 'resources/assets/js')

    .scripts([
        'libs/select2.js',
        'select2_my.js',
    ], 'public/js/select2.js', 'resources/assets/js')

    .scripts([
        'libs/sb-admin-2.js',
    ], 'public/js/sb-admin-2.js', 'resources/assets/js')

    .scripts([
        'google-map_my.js',
    ], 'public/js/google-map_my.js', 'resources/assets/js')

    .scripts([
        'libs/jquery-ui.js',
        'libs/jquery.tmpl.js',
        'libs/jquery.cookie.js',
        'jquery-ui_my.js',
    ], 'public/js/jquery-ui.js', 'resources/assets/js')

    //.scripts([
    //    'libs/react.js',
    //    '../../../public/js/react_my.js',
    //], 'public/js/react.js', 'resources/assets/js')


    .version([
        'css/all.css',
        'js/all.js',
        'css/select2.css',
        'js/select2.js',
        'css/sb-admin-2.css',
        'js/sb-admin-2.js',
        'js/google-map_my.js',
        //'js/react.js',
        'js/jquery-ui.js',
    ])

    .copy('./resources/assets/css/fonts', 'public/build/fonts')
});
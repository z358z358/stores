var elixir = require('laravel-elixir');
//require('laravel-elixir-clean');
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

    //.clean()

    //.react("react_my.jsx")

    .styles([
    	'libs/bootstrap.css',
        'libs/bootstrap-social.css',
        'libs/font-awesome.css',
    	'libs/landing-page.css',
        'libs/select2.css',
        'libs/sb-admin-2.css',
        
        'my.css',
    ], 'public/css/all.css', 'resources/assets/css')

    .scripts([
    	'libs/jquery.js',
    	'libs/bootstrap.js',
        'libs/jquery.timeago.js',
        'libs/select2.js',    
        'libs/sb-admin-2.js',        
        'libs/jquery-ui.js',
        'libs/jquery.tmpl.js',
        'libs/jquery.cookie.js',
        //'libs/vue.0.12.js',
        'libs/vue.1.08.js',

        'google-map_my.js',
        'select2_my.js',
        //'item_my.js',
        //'order_my.js',
    ], 'public/js/all.js', 'resources/assets/js')

    //.scripts([
    //    'libs/react.js',
    //    '../../../public/js/react_my.js',
    //], 'public/js/react.js', 'resources/assets/js')

    .version([
        'css/all.css',
        'js/all.js',
    ])

    .copy('./resources/assets/css/fonts', 'public/build/fonts')
});
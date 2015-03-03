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
    	'libs/landing-page.css'
    ]);

    mix.scripts([
    	'libs/jquery.js',
    	'libs/bootstrap.min.js',
    ]);

    mix.version(['css/all.css', 'js/all.js']);

    mix.copy('./resources/css/fonts/**', 'public/build/fonts');
});

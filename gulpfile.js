var elixir = require('laravel-elixir');
var fs = require('fs');

var deleteFolderRecursive = function(path) {
  if( fs.existsSync(path) ) {
    fs.readdirSync(path).forEach(function(file,index){
      var curPath = path + "/" + file;
      if(fs.lstatSync(curPath).isDirectory()) { // recurse
        deleteFolderRecursive(curPath);
      } else { // delete file
        fs.unlinkSync(curPath);
      }
    });
    fs.rmdirSync(path);
  }
};

deleteFolderRecursive('public/css');
deleteFolderRecursive('public/js');
deleteFolderRecursive('public/build');

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
    //mix.sass('app.scss', 'resources/css');

    mix.styles([
    	'libs/bootstrap.min.css',
        'libs/bootstrap-social.css',
        'libs/font-awesome.min.css',
    	'libs/landing-page.css',
        'my.css',
    ], 'public/css/all.css')

    .scripts([
    	'libs/jquery.js',
    	'libs/bootstrap.js',
    ], 'public/js/all.js')

    .styles([
        'libs/select2.css',
    ], 'public/css/select2.css')

    .scripts([
        'libs/select2.js',
        'select2_my.js',
    ], 'public/js/select2.js')

    .styles([
        'libs/sb-admin-2.css',
    ], 'public/css/sb-admin-2.css')

    .scripts([
        'libs/sb-admin-2.js',
    ], 'public/js/sb-admin-2.js')

    .scripts([
        'google-map_my.js',
    ], 'public/js/google-map_my.js')

    .version([
        'css/all.css',
        'js/all.js',
        'css/select2.css',
        'js/select2.js',
        'css/sb-admin-2.css',
        'js/sb-admin-2.js',
        'js/google-map_my.js',
    ])

    .copy('./resources/css/fonts/**', 'public/build/fonts');
});

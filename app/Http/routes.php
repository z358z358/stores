<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

Route::get('/', 'WelcomeController@index');

Route::get('home', 'HomeController@index');

Route::resource('settings', 'SettingsController');
Route::get('settings/email/emailProveSend', ['as' => 'emailProveSend', 'uses' => 'SettingsController@emailProveSend']);
Route::get('settings/email/emailProve/{email_token}', ['as' => 'emailProveCheck', 'uses' => 'SettingsController@emailProveCheck']);


Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

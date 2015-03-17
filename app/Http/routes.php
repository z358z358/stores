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
Route::controllers([
	'auth' => 'Auth\AuthController',
	'password' => 'Auth\PasswordController',
]);

/**
 * home
 */
Route::group(['namespace' => 'Home'], function()
{
	Route::get('/', 'HomeController@index');

	Route::get('home', 'HomeController@index');

	Route::resource('settings', 'SettingsController');
	Route::get('settings/email/emailProveSend', ['as' => 'emailProveSend', 'uses' => 'SettingsController@emailProveSend']);
	Route::get('settings/email/emailProve/{email_token}', ['as' => 'emailProveCheck', 'uses' => 'SettingsController@emailProveCheck']);

	Route::resource('store', 'StoreController');
	Route::get('store_id/{store}', ['as' => 'store.showById', 'uses' =>'StoreController@showById']);

	Route::get('tag/{slug}/{name?}', ['as' => 'tag.show', 'uses' =>'TagController@show']);
	Route::get('{slug}/{name?}', ['as' => 'store.slug', 'uses' =>'StoreController@show']);
});


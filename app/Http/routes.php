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

	Route::resource('order', 'OrderController');

	Route::resource('store', 'StoreController');
	Route::get('store_id/{store}', ['as' => 'store.showById', 'uses' =>'StoreController@showById']);

	Route::get('tag/{slug}/{name?}', ['as' => 'tag.show', 'uses' =>'TagController@show']);

	Route::get('menuEdit/{store}', ['as' => 'menu.edit', 'uses' => 'MenuController@edit']);
	Route::post('menuEdit/{store}', ['as' => 'menu.update', 'uses' => 'MenuController@update']);

	Route::get('{storeSlug}/menu', ['as' => 'menu.show', 'uses' => 'MenuController@show']);
	Route::post('{storeSlug}/menu/submit', ['as' => 'menu.submit', 'uses' => 'MenuController@submit']);		
	Route::get('{storeSlug}/{name?}', ['as' => 'store.slug', 'uses' =>'StoreController@show']);

});


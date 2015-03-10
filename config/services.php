<?php

return [

	/*
	|--------------------------------------------------------------------------
	| Third Party Services
	|--------------------------------------------------------------------------
	|
	| This file is for storing the credentials for third party services such
	| as Stripe, Mailgun, Mandrill, and others. This file provides a sane
	| default location for this type of information, allowing packages
	| to have a conventional place to find your various credentials.
	|
	*/

	'mailgun' => [
		'domain' => env('SERVICES_MAILGUN_DOMAIN', 'sandbox03d59e6c3b9d4f69892f53713e1c2664.mailgun.org'),
		'secret' => env('SERVICES_MAILGUN_SECRET', 'key-9c975fd765823b92207230555edac0d1'),
	],

	'mandrill' => [
		'secret' => '',
	],

	'ses' => [
		'key' => '',
		'secret' => '',
		'region' => 'us-east-1',
	],

	'stripe' => [
		'model'  => 'User',
		'secret' => '',
	],

	'facebook' => [
		'client_id' => env('FACEBOOK_ID'),
	    'client_secret' => env('FACEBOOK_SECRET'),
	    'redirect' => env('FACEBOOK_REDIRECT'),
	],

];

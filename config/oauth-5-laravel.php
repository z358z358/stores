<?php

return [

	/*
	|--------------------------------------------------------------------------
	| oAuth Config
	|--------------------------------------------------------------------------
	*/

	/**
	 * Storage
	 */
	'storage' => 'Session',

	/**
	 * Consumers
	 */
	'consumers' => [

		'Facebook' => [
			'client_id'     => env('FACEBOOK_ID'),
			'client_secret' => env('FACEBOOK_SECRET'),
			'scope'         => [],
		],

	]

];
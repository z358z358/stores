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
			'client_id'     => env('FACEBOOK_ID', '482845198525908'),
			'client_secret' => env('FACEBOOK_SECRET', '74766fe2e70e34f037797127798e9c06'),
			'scope'         => [],
		],

	]

];
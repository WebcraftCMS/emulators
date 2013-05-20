<?php

return array(

	/*
	|--------------------------------------------------------------------------
	| Server Emulator
	|--------------------------------------------------------------------------
	|
	| The emulator that the server uses. The following emulators are
	| supported:
	|
	| trinity-cataclysm
	|
	*/

	'emulator' => 'trinity-cataclysm',

	/*
	|--------------------------------------------------------------------------
	| Accounts
	|--------------------------------------------------------------------------
	|
	| The database for the server's accounts.
	|
	*/

	'accounts' => array(
		'driver'    => 'mysql',
		'host'      => 'localhost',
		'database'  => 'trinity_accounts',
		'username'  => 'root',
		'password'  => '',
		'charset'   => 'utf8',
		'collation' => 'utf8_unicode_ci',
		'prefix'    => '',
	),

	/*
	|--------------------------------------------------------------------------
	| Realms
	|--------------------------------------------------------------------------
	|
	| All of the server's realms. Each realm should include a characters and
	| world database.
	|
	*/

	'realms' => array(

		'netherstorm' => array(

			'characters' => array(
				'driver'    => 'mysql',
				'host'      => 'localhost',
				'database'  => 'trinity_characters',
				'username'  => 'root',
				'password'  => '',
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',	
			),

			'world' => array(
				'driver'    => 'mysql',
				'host'      => 'localhost',
				'database'  => 'trinity_world',
				'username'  => 'root',
				'password'  => '',
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',	
			),
		),
	),
);

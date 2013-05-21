### Configuration

Before you can start using the library, you will need to supply the
following configs:

#### Emulator

The server's emulator. The supported emulators are:

	trinity-cataclysm

#### Accounts

The connection information for your server's accounts.

#### Realms

The list of connections for each realm.

### Example Configuration

```php

array(

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
		'database'  => 'accounts',
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
				'database'  => 'characters',
				'username'  => 'root',
				'password'  => '',
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',	
			),

			'world' => array(
				'driver'    => 'mysql',
				'host'      => 'localhost',
				'database'  => 'world',
				'username'  => 'root',
				'password'  => '',
				'charset'   => 'utf8',
				'collation' => 'utf8_unicode_ci',
				'prefix'    => '',	
			),
		),
	),
);
```
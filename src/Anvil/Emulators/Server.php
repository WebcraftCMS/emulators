<?php namespace Anvil\Emulators;

use Illuminate\Database\DatabaseManager;

class Server {

	/**
	 * The database's connection manager.
	 *
	 * @var \Illuminate\Database\DatabaseManager
	 */
	protected static $database;

	/**
	 * The realm's connections.
	 *
	 * @var array
	 */
	protected $connections;

	/**
	 * The server's realms.
	 *
	 * @var array
	 */
	protected $realms = array();

	/**
	 * Create a new instance.
	 *
	 * @param  array  $config
	 * @return void
	 */
	public function __construct(array $config)
	{
		$this->config = $config;

		$this->registerDefaultEmulators();
	}

	/**
	 * Get a realm from the server.
	 *
	 * @param  string  $name
	 * @return \Anvil\Emulators\Realm
	 */
	public function getRealm($name)
	{
		if( ! isset($this->realms[$name]))
		{
			$this->realms[$name] = new Realm($name, $this->getEmulator());
		}

		return $this->realms[$name];
	}

	/**
	 * Get all of the realms on the server.
	 *
	 * @return array
	 */
	public function getRealms()
	{
		$realms = array();

		// We need to load all of the realms. Just in case that not all
		// of the realms have been loaded yet, let's manually request
		// each realm.
		foreach(array_keys($this->config['realms']) as $realmName)
		{
			$realms[$realmName] = $this->realm($realmName);
		}

		return $realms;
	}

	/**
	 * Create a new account.
	 *
	 * @param  array  $attributes
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function createAccount(array $attributes = array())
	{
		return $this->getEmulator()->getModel('account', $attributes);
	}

	/**
	 * Get an account.
	 *
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function account()
	{
		return $this->getEmulator()->getQueryBuilder('account');
	}

	/**
	 * Add an emulator.
	 *
	 * @param  string  $name
	 * @param  \Anvil\Emulators\Emulator  $emulator
	 * @return void
	 */
	public function addEmulator($name, Emulator $emulator)
	{
		$this->emulators[$name] = $emulator;
	}

	/**
	 * Get an emulator.
	 *
	 * @param  string|null  $name
	 * @return \Anvil\Emulators\Emulator
	 */
	public function getEmulator($name = null)
	{
		$name = $name ?: $this->config['emulator'];

		return $this->emulators[$name];
	}

	/**
	 * Grab a database connection.
	 *
	 * @return \Illuminate\Database\Connection
	 */
	public function connection($name)
	{
		return static::$database->connection($name);
	}

	/**
	 * Register the default emulators.
	 *
	 * @return void
	 */
	protected function registerDefaultEmulators()
	{
		$this->addEmulator('trinity-cataclysm', new Trinity\Cataclysm\Emulator);
	}


	/**
	 * Set the database's connection manager.
	 *
	 * @param  \Illuminate\Database\DatabaseManager  $database
	 * @return void
	 */
	public static function setConnectionResolver(DatabaseManager $database)
	{
		static::$database = $database;
	}
}

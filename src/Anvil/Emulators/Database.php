<?php namespace Anvil\Emulators;

use PDO;
use Illuminate\Database\Capsule;
use Illuminate\Events\Dispatcher;

class Database extends Capsule {

	/**
	 * Create a new database capsule instance.
	 *
	 * @param  array  $server
	 * @param  \Illuminate\Events\Dispatcher|null  $dispatcher
	 * @return void
	 */
	public function __construct(array $server, Dispatcher $dispatcher = null)
	{
		parent::__construct($this->buildConfig($server), $dispatcher);

		// Let's inject the database handlers to the classes that depend on them.
		$this->bootEloquent();
		$this->bootServer();
		$this->bootRealms();
	}

	/**
	 * Build the necessary configuration array for Illuminate's database.
	 *
	 * @param  array  $server
	 * @return array
	 */
	protected function buildConfig(array $server)
	{
		$connections = array(
			'accounts' => $server['accounts'],
		);

		// Let's grab all of the database connections from the realms.
		foreach($server['realms'] as $realm => $databases)
		{
			foreach($databases as $name => $connection)
			{
				$connections[$realm.'.'.$name] = $connection;
			}
		}

		// Now that we have the database connections, let's return
		// the configuration that Illuminate's database component needs.
		return array(
			'fetch' => PDO::FETCH_CLASS,
			'default' => 'accounts',
			'connections' => $connections,
		);
	}

	/**
	 * Inject the server's database dependencies.
	 *
	 * @return void
	 */
	public function bootServer()
	{
		Server::setConnectionResolver($this->manager);
	}

	/**
	 * Inject the realms' database dependencies.
	 *
	 * @return void
	 */
	public function bootRealms()
	{
		Realm::setConnectionResolver($this->manager);
	}

	/**
	 * Prepare the database.
	 *
	 * @param  array $connections
	 */
	public static function prepare(array $connections)
	{
		new static($connections);
	}
}

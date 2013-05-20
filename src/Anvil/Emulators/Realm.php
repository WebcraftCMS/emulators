<?php namespace Anvil\Emulators;

use Closure;
use Illuminate\Database\DatabaseManager;

class Realm {

	/**
	 * The database's connection manager.
	 *
	 * @var \Illuminate\Database\DatabaseManager
	 */
	protected static $database;

	/**
	 * The realm's name.
	 *
	 * @var string
	 */
	protected $name;

	/**
	 * The realm's emulator.
	 *
	 * @var string
	 */
	protected $emulator;

	/**
	 * The emulators' models.
	 *
	 * @var array
	 */
	protected $models = array(
		'character',
		'guild',
	);

	/**
	 * Create a new instance.
	 *
	 * @param  string  $name
	 * @param  string  $emulator
	 */
	public function __construct($name, $emulator)
	{
		$this->name = $name;
		$this->emulator = $emulator;
	}

	/**
	 * Fetch a model from the emulator.
	 *
	 * @param  string  $method
	 * @param  array  $arguments
	 * @return \Illuminate\Database\Eloquent\Model|\Illuminate\Database\Eloquent\Builder
	 */
	public function __call($method, $arguments)
	{
		// The model will need to know the realm that it needs
		// to connect to. Let's set the realm's name now.
		$this->emulator->setRealm($this->name);

		// If the method name begins with "method" then we
		// want to create a new record. For this we need a
		// fresh Eloquent model.
		if(strpos($method, 'create') === 0)
		{
			// The model's name will be the second word in the
			// method's name. So, let's get rid of the create
			// prefix. Also, we need to lowercase the model's name
			// as the method may be camel-cased.
			$model = strtolower(substr($method, 6));

			if(in_array($model, $this->models))
			{
				return $this->emulator->getModel($model);
			}
		}

		// Otherwise we are reading, editing, or deleting an
		// existing record. For this we will need the query
		// builder.
		else
		{
			foreach($this->models as $model)
			{
				if(($position = strpos($method, $model)) === 0)
				{
					return $this->emulator->getQueryBuilder($model);
				}
			}
		}

		throw new \Exception("Method [$method] does not exist.");
	}

	/**
	 * Grab the realm's connection.
	 *
	 * @return \Illuminate\Database\Connection
	 */
	public function connection()
	{
		return static::$database->connection($this->name);
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

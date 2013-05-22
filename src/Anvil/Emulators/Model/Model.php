<?php namespace Anvil\Emulators\Model;

use Illuminate\Database\Eloquent\Model as IlluminateModel;

class Model extends IlluminateModel {

	/**
	 * The database that the model uses.
	 *
	 * @var string
	 */
	public $database;

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Set the realm for the next query.
	 *
	 * @param  string  $realm
	 * @return \Anvil\Emulators\Model
	 */
	public function setRealm($realm)
	{
		$this->connection = $realm.'.'.$this->database;

		return $this;
	}

	/**
	 * Set the realm for the next query.
	 *
	 * @return string
	 */
	public function getRealm()
	{
		return rtrim($this->connection, $this->database.'.');
	}

}
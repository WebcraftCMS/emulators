<?php namespace Anvil\Emulators\Eloquent;

use Illuminate\Database\Eloquent\Model as IlluminateModel;

class Model extends IlluminateModel {

	/**
	 * The database that the model uses.
	 *
	 * @var string
	 */
	public $database;

	/**
	 * The aliases for attributes.
	 *
	 * @var array
	 */
	public $attributeAliases = array();

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

	/**
	 * Get a new query builder for the model's table.
	 *
	 * @param  bool  $excludeDeleted
	 * @return \Anvil\Emulators\Eloquent\Builder
	 */
	public function newQuery($excludeDeleted = true)
	{
		$builder = new Builder($this->newBaseQueryBuilder());

		// Once we have the query builders, we will set the model instances so the
		// builder can easily access any information it may need from the model
		// while it is constructing and executing various queries against it.
		$builder->setModel($this)->with($this->with);

		if ($excludeDeleted and $this->softDelete)
		{
			$builder->whereNull($this->getQualifiedDeletedAtColumn());
		}

		return $builder;
	}

	/**
	 * Get an attribute from the model.
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	public function getAttribute($key)
	{
		$key = $this->getAttributeFromAlias($key);

		return parent::getAttribute($key);
	}

	/**
	 * Get a plain attribute (not a relationship).
	 *
	 * @param  string  $key
	 * @return mixed
	 */
	protected function getAttributeValue($key)
	{
		$key = $this->getAttributeFromAlias($key);

		return parent::getAttributeValue($key);
	}

	/**
	 * Set a given attribute on the model.
	 *
	 * @param  string  $key
	 * @param  mixed   $value
	 * @return void
	 */
	public function setAttribute($key, $value)
	{
		$key = $this->getAttributeFromAlias($key);

		return parent::setAttribute($key, $value);
	}

	/**
	 * Convert the model's attributes to an array.
	 *
	 * @return array
	 */
	public function attributesToArray()
	{
		$attributes = parent::attributesToArray();

		// Let's go through each element and use the attribute's aliases.
		foreach($attributes as $attribute => $value)
		{
			// Does the current attribute have an alias?
			if(isset($this->attributeAliases[$attribute]))
			{
				$alias = $this->attributeAliases[$attribute];

				$attributes[$alias] = $attributes[$attribute];

				unset($attributes[$attribute]);
			}
		}

		return $attributes;
	}

	/**
	 * Get an attribute's real name.
	 *
	 * @param  string  $key
	 * @return string
	 */
	public function getAttributeFromAlias($key)
	{
		// Let's search for the key in the list of aliases. If we find a 
		// match, return the aliase's corresponding attribute.
		if($attribute = array_search($key, $this->attributeAliases))
		{
			return $attribute;
		}

		else
		{
			// A match could not be found. Therefore, the key does not have
			// an alias.
			return $key;
		}
	}
}
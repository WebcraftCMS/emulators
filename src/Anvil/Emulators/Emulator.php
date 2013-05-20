<?php namespace Anvil\Emulators;

use Illuminate\Database\Eloquent\Model;

abstract class Emulator {

	/**
	 * The emulator's loaded models.
	 *
	 * @var array
	 */
	protected $models = array();

	/**
	 * The current realm the model should be for.
	 *
	 * @var string
	 */
	protected $currentRealm;

	/**
	 * Set the realm that the query will be for.
	 *
	 * @param  string  $name
	 * @return \Anvil\Emulators\Emulator
	 */
	public function setRealm($name)
	{
		$this->currentRealm = $name;

		return $this;
	}

	/**
	 * Get a model.
	 *
	 * @param  string  $name
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function getModel($model, array $attributes = array())
	{
		$model = $this->getInstance($model, $attributes);
		$model = $this->prepareModel($model);

		return $model;
	}

	/**
	 * Get a model's query builder.
	 *
	 * @param  string  $model
	 * @param  array  $attributes
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function getQueryBuilder($name)
	{
		// We don't need to grab a new model every time we want to
		// execute a new query. Let's internally cache an instance of
		// the model to improve memory performance.
		if( ! isset($this->models[$name]))
		{
			$this->models[$name] = $this->getInstance($name);
		}

		$model = $this->models[$name];
		$model = $this->prepareModel($model);

		return $model->newQuery();
	}

	/**
	 * Prepare a model.
	 *
	 * @param  \Illuminate\Database\Eloquent\Model  $model
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	protected function prepareModel(Model $model)
	{
		// If the current query is intended for a specific realm,
		// let's let the model know.
		if( ! is_null($this->currentRealm))
		{
			if(method_exists($model, 'setRealm'))
			{
				$model->setRealm($this->currentRealm);
			}

			$this->currentRealm = null;
		}

		return $model;
	}

	/**
	 * Get a model's instance.
	 *
	 * @param  string  $model
	 * @param  array  $attributes
	 * @param  \Illuminate\Database\Eloquent\Model
	 */
	abstract public function getInstance($model, array $attributes = array());
}

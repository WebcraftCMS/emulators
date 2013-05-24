<?php namespace Anvil\Emulators\Eloquent;

use Illuminate\Database\Eloquent\Builder as IlluminateBuilder;

class Builder extends IlluminateBuilder {

	/**
	 * Check if a method uses attribute aliases.
	 *
	 * @param  string  $method
	 * @return bool
	 */
	protected function methodUsesAliases($method)
	{
		if( ! empty($this->model->attributeAliases))
		{
			return in_array($method, array('where'));
		}

		return false;
	}

	/**
	 * Dynamically handle calls into the query instance.
	 *
	 * @param  string  $method
	 * @param  array   $parameters
	 * @return mixed
	 */
	public function __call($method, $parameters)
	{
		// Get the attribute's real name if the method supports
		// attribute aliasing.
		if($this->methodUsesAliases($method))
		{
			$parameters[0] = $this->model->getAttributeFromAlias($parameters[0]);				
		}

		return parent::__call($method, $parameters);
	}

}
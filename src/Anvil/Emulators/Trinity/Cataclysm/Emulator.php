<?php namespace Anvil\Emulators\Trinity\Cataclysm;

use Anvil\Emulators\Emulator as AnvilEmulator;

class Emulator extends AnvilEmulator {

	/**
	 * Get a model's instance.
	 *
	 * @param  string  $model
	 * @param  \Illuminate\Database\Eloquent\Model
	 */
	public function getInstance($model, array $attributes = array())
	{
		switch($model)
		{
			case 'account':
				return new Model\Account($attributes);

			case 'arenateam':
				return new Model\ArenaTeam($attributes);

			case 'character':
				return new Model\Character($attributes);

			case 'guild':
				return new Model\Guild($attributes);
		}

		throw new \InvalidArgumentException("Unsupported model [$model].");
	}
}

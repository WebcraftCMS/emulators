<?php namespace Anvil\Emulators\Trinity\Cataclysm;

use Anvil\Emulators\Eloquent\Model;

class ArenaTeam extends Model {

	/**
	 * The database that the model uses.
	 *
	 * @var string
	 */
	public $database = 'characters';

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'arena_team';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'arenaTeamId';

	/**
	 * Get the team's captain.
	 *
	 * @return  \Anvil\Emulators\Trinity\Cataclysm\Model\Character
	 */
	public function getCaptainAttribute()
	{
		$character = new Character;

		$character->setRealm($this->getRealm())
			->where('guid', $this->attributes['captainGuid'])
			->first();
	}
}
<?php namespace Anvil\Emulators\Trinity\Cataclysm;

use Anvil\Emulators\Eloquent\Model;
use Anvil\Emulators\Eloquent\Builder as Query;

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

	/**
	 * Get only twos teams.
	 *
	 * @param  \Anvil\Emulators\Eloquent\Builder  $query
	 * @return \Anvil\Emulators\Eloquent\Builder
	 */
	public function scopeTwos(Query $query)
	{
		return $query->where('type', 2);
	}

	/**
	 * Get only threes teams.
	 *
	 * @param  \Anvil\Emulators\Eloquent\Builder  $query
	 * @return \Anvil\Emulators\Eloquent\Builder
	 */
	public function scopeThrees(Query $query)
	{
		return $query->where('type', 3);
	}

	/**
	 * Get only fives teams.
	 *
	 * @param  \Anvil\Emulators\Eloquent\Builder  $query
	 * @return \Anvil\Emulators\Eloquent\Builder
	 */
	public function scopeFives(Query $query)
	{
		return $query->where('type', 5);
	}
}
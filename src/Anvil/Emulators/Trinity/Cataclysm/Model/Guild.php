<?php namespace Anvil\Emulators\Trinity\Cataclysm\Model;

use Anvil\Emulators\Model\Model;

class Guild extends Model {

	/*
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
	protected $table = 'guild';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'guildid';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Select a guild by ID.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  int  $id
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWhereId(Query $query, $id)
	{
		return $query->where('guildid', '=', $id);
	}

	/**
	 * Set a guild's ID.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function setIdAttribute($id)
	{
		$this->attributes['guildid'] = $id;
	}

	/**
	 * Get a guild's ID.
	 *
	 * @return bool
	 */
	public function getIdAttribute()
	{
		return $this->attributes['guildid'];
	}

	/**
	 * Set the guild leader.
	 *
	 * @param  \Anvil\Emulators\Trinity\Cataclysm\Model\Character|int  $leader
	 */
	public function setLeaderAttribute($leader)
	{
		if($leader instanceof Character)
		{
			$leader = $leader->id;
		}

		$this->attributes['leaderguid'] = $leader;
	}

	/**
	 * Get the guild leader.
	 *
	 * @return \Anvil\Emulators\Trinity\Cataclysm\Model\Character
	 */
	public function getLeaderAttribute()
	{
		$character = new Character;

		return $character->setRealm($this->getRealm())
					->where('guid', $this->attributes['leaderguid'])
					->first();
	}

	/**
	 * Get the guild's characters.
	 *
	 * @return Illuminate\Support\Collection
	 */
	public function getCharactersAttributes()
	{
		$members = $this->getConnection()
						->table('guild_member')
						->select('guid')
						->where('guildid', $this->attributes['guildid'])
						->get();

		$characters = new Character;

		return $character->setRealm($this->getRealm())
				->whereIn('guid', array_pluck($characters, 'guid'))
				->get();
	}
}

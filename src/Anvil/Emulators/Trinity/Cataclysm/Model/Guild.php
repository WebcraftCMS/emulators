<?php namespace Anvil\Emulators\Trinity\Cataclysm\Model;

use Anvil\Emulators\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as Query;

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
	 * The aliases for attributes.
	 *
	 * @var array
	 */
	public $attributeAliases = array(

		'guildid' => 'id',
	);

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
	public function getMembersAttribute()
	{
		$members = $this->getConnection()
						->table('guild_member')
						->select('guid')
						->where('guildid', $this->attributes['guildid'])
						->get();

		$characters = new Character;

		return $characters->setRealm($this->getRealm())
				->whereIn('guid', array_pluck($members, 'guid'));
	}
}

<?php namespace Anvil\Emulators\Trinity\Cataclysm\Model;

use Anvil\Emulators\Model\Model;
use Illuminate\Database\Eloquent\Builder as Query;

use Anvil\Emulators\Model\CharacterInterface;

class Character extends Model implements CharacterInterface {

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
	protected $table = 'characters';

	/**
	 * The primary key for the model.
	 *
	 * @var string
	 */
	protected $primaryKey = 'guid';

	/**
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

	/**
	 * Boot the model.
	 *
	 * @return void
	 */
	public static function boot()
	{
		parent::boot();

		// Let's do a little bit of cleanup to the database if a
		// character is deleted.
		Character::deleting(function($user)
		{
			$user->leaveGuild();
		});
	}

	/**
	 * Select a character by ID.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  int  $id
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWhereId(Query $query, $id)
	{
		return $query->where('guid', '=', $id);
	}

	/**
	 * Set a character's ID.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function setIdAttribute($id)
	{
		$this->attributes['guid'] = $id;
	}

	/**
	 * Get a character's ID.
	 *
	 * @return bool
	 */
	public function getIdAttribute()
	{
		return $this->attributes['guid'];
	}

	/**
	 * Select a character by account ID.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  int  $id
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWhereAccountId(Query $query, $id)
	{
		return $query->where('account', '=', $id);
	}

	/**
	 * Set a character's account ID.
	 *
	 * @param  int  $id
	 * @return bool
	 */
	public function setAccountIdAttribute($id)
	{
		$this->attributes['account'] = $id;
	}

	/**
	 * Get a character's account ID.
	 *
	 * @return bool
	 */
	public function getAccountIdAttribute()
	{
		return $this->attributes['account'];
	}

	/**
	 * Set the character's account.
	 *
	 * @param  \Anvil\Emulators\Trinity\Cataclysm\Model\Account|int  $account
	 * @return void
	 */
	public function setAccountAttribute($account)
	{
		// If the account is an instance of the Account model, let's grab
		// the account's ID.
		if($account instanceof Account)
		{
			$account = $account->id;
		}

		$this->attributes['account'] = $account;
	}

	/**
	 * Get the character's account.
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function getAccountAttribute()
	{
		return Account::find($this->attributes['account']);
	}

	/**
	 * Get an account that is online.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  bool  $online
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeOnline(Query $query, $online = true)
	{
		return $query->where('online', '=', $online ? 1 : 0);
	}

	/**
	 * Check if an account is online.
	 *
	 * @return bool
	 */
	public function getIsOnlineAttribute()
	{
		return $this->attributes['online'] == true;
	}

	/**
	 * Get an account that is offline.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  bool  $offline
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeOffline(Query $query, $offline = true)
	{
		return $this->scopeOnline($query, ! $offline);
	}

	/**
	 * Check if an account is offline.
	 *
	 * @return bool
	 */
	public function getIsOfflineAttribute()
	{
		return $this->attributes['online'] == false;
	}

	/**
	 * Force the character to be renamed at login.
	 *
	 * @return Anvil\Emulators\Trinity\Cataclysm\Character
	 */
	public function forceRename()
	{
		return $this->addFlag(1);
	}

	/**
	 * Force the character to change faction at login.
	 *
	 * @return Anvil\Emulators\Trinity\Cataclysm\Character
	 */
	public function forceFactionChange()
	{
		return $this->addFlag(64);
	}

	/**
	 * Force the character to change race at login.
	 *
	 * @return Anvil\Emulators\Trinity\Cataclysm\Character
	 */
	public function forceRaceChange()
	{
		return $this->addFlag(128);
	}

	/**
	 * Add a flag to the character's at login attribute
	 *
	 * @param  int  $flag
	 * @return Anvil\Emulators\Trinity\Cataclysm\Character
	 */
	protected function addFlag($flag)
	{
		// First, let's convert the current flags into decimal so that
		// they are a little easier to work with.
		$flags = $this->attributes['at_login'];

		// Let's check if the current flag is already set in the character's
		// flags. We can't turn on a flag more than once, otherwise that will
		// turn on other flags.
		if(($flags & $flag) == 0)
		{
			$this->attributes['at_login'] = $flags + $flag;
		}

		return $this;
	}

	/**
	 * Set the character's guild.
	 *
	 * @param  \Anvil\Emulators\Trinity\Cataclysm\Model\Guild|int|null  $guild
	 * @param void
	 */
	public function setGuildAttribute($guild)
	{
		// Leave the current guild if the guild attribute was nulled.
		if(is_null($guild))
		{
			$this->leaveGuild();
		}

		// Otherwise, let's join the guild.
		else
		{
			$this->joinGuild($guild);
		}
	}

	/**
	 * Get a character's guild.
	 *
	 * @return Anvil\Emulators\Trinity\Cataclysm\Guild|null
	 */
	public function getGuildAttribute()
	{
		$member = $this->getConnection()
					->table('guild_member')
					->where('guid', $this->attributes['guid'])
					->first();

		// If the character does not belong to a guild, the query
		// will find no results and will return null.
		if(is_null($member))
		{
			return null;
		}

		// Otherwise, the character has a guild. Let's fetch the guild
		// now.
		else
		{
			$guild = new Guild;

			return $guild->setRealm($this->getRealm())
						->where('guildid', $member->guildid)
						->first();
		}
	}

	/**
	 * Make the character join a guild.
	 *
	 * @param  \Anvil\Emulators\Trinity\Cataclysm\Model\Guild|int  $guild
	 * @return \Anvil\Emulators\Trinity\Cataclysm\Character
	 */
	public function joinGuild($guild)
	{
		// Make sure the user actually exists before trying to make it join
		// a guild.
		if($this->exists)
		{
			// We need to get the guild's ID. If we were given a guild model,
			// let's fetch the ID from it.
			if($guild instanceof Guild)
			{
				$guild = $guild->id;
			}

			// Make sure the character is not in a guild before joining a new one.
			$this->leaveGuild();

			// Now, add the character into the new guild.
			$this->getConnection()
				->table('guild_member')
				->insert(array(
					'guildid' => $guild,
					'guid' => $this->attributes['guid']
				));
		}

		else
		{
			throw new \Exception("Character must exist before adding to guild.");
		}

		return $this;
	}

	/**
	 * Make the character leave a guild.
	 *
	 * @return \Anvil\Emulators\Trinity\Cataclysm\Character
	 */
	public function leaveGuild()
	{
		// If the user does not exist, it cannot be in a guild.
		if($this->exists)
		{
			$this->getConnection()
				->table('guild_member')
				->where('guid', $this->attributes['guid'])
				->delete();
		}

		return $this;
	}
}

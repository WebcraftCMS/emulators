<?php namespace Anvil\Emulators\Trinity\Cataclysm\Model;

use Anvil\Emulators\Eloquent\Model;
use Anvil\Emulators\Eloquent\Builder as Query;

use Anvil\Emulators\Model\AccountInterface;

class Account extends Model implements AccountInterface {

	/**
	 * The connection name for the model.
	 *
	 * @var string
	 */
	protected $connection = 'accounts';

	/**
	 * The table associated with the model.
	 *
	 * @var string
	 */
	protected $table = 'account';

	/**
	 * The aliases for attributes.
	 *
	 * @var array
	 */
	public $attributeAliases = array(

		'ipAddress' => 'last_ip',
		'online' => 'isOnline',
	);

	/**
	 * Set the password attribute.
	 *
	 * @param  string  $password
	 * @return void
	 */
	public function setPasswordAttribute($password)
	{
		$password = $this->encryptPassword($this->attributes['username'], $password);

		$this->attributes['sha_pass_hash'] = $password;
	}

	/**
	 * Check a password.
	 *
	 * @param  string  $password
	 */
	public function checkPassword($password)
	{
		$encryptedPassword = $this->encryptPassword($this->attributes['username'], $password);

		return ($encryptedPassword == $this->attributes['sha_pass_hash']);
	}

	/**
	 * Encrypt a password.
	 *
	 * @param  string  $username
	 * @param  string  $password
	 */
	protected function encryptPassword($username, $password)
	{
		$username = strtoupper($username);
		$password = strtoupper($password);

		return sha1($username.':'.$password);
	}

	/**
	 * Check if an account is online.
	 *
	 * @return bool
	 */
	public function getOnlineAttribute()
	{
		return $this->attributes['online'] == 1;
	}

	/**
	 * Get an account that is offline.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  bool  $offline
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWhereIsOffline(Query $query, $offline = true)
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
		return ! $this->getOnlineAttribute();
	}

	/**
	 * Ban the account.
	 *
	 * @return void
	 */
	public function ban()
	{
		$id = $this->attributes['id'];
		$active = 1;

		$this->getConnection()
			->table('account_banned')
			->insert(compact('id', 'active'));
	}
}

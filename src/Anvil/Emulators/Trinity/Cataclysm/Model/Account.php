<?php namespace Anvil\Emulators\Trinity\Cataclysm\Model;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder as Query;

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
	 * Indicates if the model should be timestamped.
	 *
	 * @var bool
	 */
	public $timestamps = false;

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
	 * Set the account's IP address.
	 *
	 * @param  string  $ipAddress
	 * @return void
	 */
	public function setIpAddressAttribute($ipAddress)
	{
		$this->attributes['last_ip'] = $ipAddress;
	}

	/**
	 * Get an account with a specific IP address.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  string  $ipAddress
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWhereIpAddress($query, $ipAddress)
	{
		return $query->where('last_ip', $ipAddress);
	}

	/**
	 * Get the account's IP address.
	 *
	 * @return string
	 */
	public function getIpAddressAttribute()
	{
		return $this->attributes['last_ip'];
	}

	/**
	 * Get an account that is online.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  bool  $online
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	public function scopeWhereIsOnline(Query $query, $online = true)
	{
		return $query->where('online', '=', $online ? 1 : 0);
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
	 * Check if an account is online.
	 *
	 * @return bool
	 */
	public function getIsOnlineAttribute()
	{
		return $this->attributes['online'] == true;
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

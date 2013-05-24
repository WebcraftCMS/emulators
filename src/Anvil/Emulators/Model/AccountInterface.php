<?php namespace Anvil\Emulators\Model;

use Illuminate\Database\Eloquent\Builder as Query;

interface AccountInterface {

	/**
	 * Check a password.
	 *
	 * @param  string  $password
	 */
	public function checkPassword($password);

	/**
	 * Get an account that is online.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @param  bool  $online
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	//public function scopeWhereIsOnline(Query $query, $online = true);

	/**
	 * Get an account that is offline.
	 *
	 * @param  \Illuminate\Database\Eloquent\Builder  $query
	 * @return \Illuminate\Database\Eloquent\Builder
	 */
	//public function scopeWhereIsOffline(Query $query);

	/**
	 * Ban the account.
	 *
	 * @return void
	 */
	public function ban();
}

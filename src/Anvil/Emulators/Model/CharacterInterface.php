<?php namespace Anvil\Emulators\Model;

use Illuminate\Database\Eloquent\Builder as Query;

interface CharacterInterface {

	/**
	 * Get a character's account.
	 *
	 * @return \Illuminate\Database\Eloquent\Model
	 */
	public function getAccountAttribute();

	/**
	 * Force the character to be renamed at login.
	 *
	 * @return void
	 */
	public function forceRename();

	/**
	 * Force the character to change faction at login.
	 *
	 * @return void
	 */
	public function forceFactionChange();

	/**
	 * Force the character to change race at login.
	 *
	 * @return void
	 */
	public function forceRaceChange();

	/**
	 * Make the character join a guild.
	 *
	 * @param  \Anvil\Emulators\Model\GuildInterface|int  $guild
	 * @return void
	 */
	public function joinGuild($guild);
}

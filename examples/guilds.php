<?php

// Let's load all of the required classes for this example.
include 'bootstrap.php';

// First, let's grab the realm that we want our character on:
$netherstorm = $server->getRealm('netherstorm');

// Let's grab the first character we can find in the database. We will use
// him as the guild master.
$firstCharacter = $netherstorm->character()->first();

// Let's make a new guild!
$guild = $netherstorm->createGuild();

$guild->name = 'My Guild';
$guild->leader = $firstCharacter;

$guild->save();

// Now that the guild exists, let's make the guild's master join the guild.
$firstCharacter->guild = $guild;

$firstCharacter->save();

// Let's find our new guild in the database.
$guild = $netherstorm->guild()
			->whereName('My Guild')
			->first();

// Now that we're done let's delete the guild.
$guild->delete();

<?php

// Let's load all of the required classes for this example.
include 'bootstrap.php';

// First, let's grab the realm that we want our character on:
$netherstorm = $server->getRealm('netherstorm');

// Creating a character is simple! Let's create a character,
// and add him to an account and guild.

// We will need the account that will own the character.
// For this example, we will just grab the very first account
// we can find.
$firstAccount = $server->account()->first();

// Let's also get the first guild we can find. Note that we
// use the same server for the guild as the character.
$firstGuild = $netherstorm->guild()->first();

// Now that we have everything that we need, let's
// create the character. Although the order does not
// matter, we will start by filling out the character's
// basic attributes first:
$character = $netherstorm->createCharacter();

$character->name = 'Foo';
$character->race = 1;
$character->class = 1;
$character->gender = 1;
$character->level = 85;

// Now, let's add the character to its account:
$character->account = $firstAccount;

// Don't forget to save the character!
$character->save();

// Now that the character is in the database, let's get it.
$character = $netherstorm->character()
				->where('name', '=', 'Foo')
				->where('id', '=', 0)
				->first();

// Now that the user exists, we can make it join a guild.
$character->guild = $firstGuild;

$character->save();

// We can look at the character's information.
echo $character->id;
echo $character->name;
echo $character->race;

var_dump($character->isOnline);
var_dump($character->isOffline);

// We can also look at the character's account. To find out more about
// the guild model, look at the accounts example.
echo $character->account->email;

// We can also look at the character's guild. To find out more about
// the guild model, look at the guilds example.
echo $character->guild->name;

// We can change the character's information.
$character->name = 'Fooz';

// We can force the character's owner to change the character when s/he attempts
// to log on to the character.
$character->forceRename();
$character->forceRaceChange();
$character->forceFactionChange();

// Don't forget to save the character when you've edited it!
$character->save();

// Lastly, let's delete the character.
$character->delete();

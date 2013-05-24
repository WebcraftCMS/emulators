<?php

// Let's load all of the required classes for this example.
include 'bootstrap.php';

// First, let's grab the realm that we want our arena teams on:
$netherstorm = $server->getRealm('netherstorm');

$arenaTeams = $netherstorm->arenaTeams();

// Let's grab the first arena team we can find.
$arenaTeam = $arenaTeams->first();

echo $arenaTeam->name;
echo $arenaTeam->rating;
echo $arenaTeam->seasonGames;
echo $arenaTeam->seasonWins;
echo $arenaTeam->weekGames;
echo $arenaTeam->weekWins;

echo $arenaTeam->captain->name;

// Let's get the name of each of the team's players.
foreach($arenaTeam->members->get() as $member)
{
	echo $member->name;
}

// You can also get the arena teams by their type like so:
$arenaTeams->twos()
	->take(5)
	->get();

$arenaTeams->threes()
	->take(5)
	->get();

$arenaTeams->fives()
	->take(5)
	->get();

// You can order teams:
$arenaTeams->orderBy('rating', 'desc')
	->take(5)
	->get();

$arenaTeams->orderBy('seasonGames', 'desc')
	->take(5)
	->get();

$arenaTeams->orderBy('weekGames', 'desc')
	->take(5)
	->get();
<?php

// Load the class autoloader.
include '../vendor/autoload.php';

// Load the config.
$config = include 'config.php';

// Load the database.
Anvil\Emulators\Database::prepare($config);

// Load the server.
$server = new Anvil\Emulators\Server($config);

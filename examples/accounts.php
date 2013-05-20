<?php

// Let's load all of the required classes for this example.
include 'bootstrap.php';

// Want to create an account? Simple:
$newAccount = $server->createAccount();

$newAccount->username = 'foo';
$newAccount->email    = 'foo@bar.com';
$newAccount->password = 'baz';

$newAccount->save();

// Let's grab the new account from the database:
$account = $server->account()
			->whereUsername('foo')
			->whereEmail('foo@bar.com')
			->first();


// Now that we have the account, let's display some of its information
echo $account->id;
echo $account->username;
echo $account->email;

// Say we wanted to log in a user, we will need to check its password:
if($account->checkPassword('baz'))
{
	// We have the correct password for the account.
}

else
{
	// Invalid password.
}

// Let's change the account's email and password.
$account->email = 'fooz@bar.com';
$account->password = 'foobar';

$account->save();

// Lastly, let's delete the account.
$account->delete();

<?php declare(strict_types=1);

use function Pest\Faker\faker;
use JoeCianflone\HasProperties\Tests\Dummy\ModelStub;
use JoeCianflone\HasProperties\Tests\Dummy\ModelStubGuarded;
use JoeCianflone\HasProperties\Tests\Dummy\ModelStubUnguarded;

$guardedUser = new ModelStubGuarded();
$guardedUser->name = faker()->name();
$guardedUser->email = faker()->safeEmail();
$guardedUser->password = faker()->password();

$normalUser = new ModelStub();
$normalUser->name = faker()->name();
$normalUser->email = faker()->safeEmail();
$normalUser->password = faker()->password();

$unguardedUser = new ModelStubUnguarded();
$unguardedUser->name = faker()->name();
$unguardedUser->email = faker()->safeEmail();
$unguardedUser->password = faker()->password();

dataset('users', [
    [$normalUser],
]);

dataset('guardedUsers', [
    [$guardedUser],
]);

dataset('unguardedUsers', [
    [$unguardedUser],
]);

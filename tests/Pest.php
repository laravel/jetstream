<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Actions\Jetstream\CreateTeam;
use Laravel\Jetstream\Tests\Fixtures\User;
use Laravel\Jetstream\Tests\OrchestraTestCase;

uses(OrchestraTestCase::class)->in('Feature', 'Unit');

function createTeam(
    $name = 'Taylor Otwell',
    $email = 'taylor@laravel.com'
) {
    $action = new CreateTeam;

    $user = User::forceCreate([
        'name' => $name,
        'email' => $email,
        'password' => 'secret',
    ]);

    return $action->create($user, ['name' => 'Test Team']);
}

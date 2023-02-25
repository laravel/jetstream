<?php

namespace Laravel\Jetstream\Tests\Unit;

use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Models\Team;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\TeamPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;
use Laravel\Jetstream\Tests\OrchestraTestCase;

beforeEach(function () {
    Gate::policy(Team::class, TeamPolicy::class);
    Jetstream::useUserModel(User::class);

    $this->artisan('migrate', ['--database' => 'testbench'])->run();
});

test('team can be deleted', function (): void {
    $team = createTeam();

    $action = new DeleteTeam;

    $action->delete($team);

    $this->assertNull($team->fresh());
});

test('team deletion can be validated', function (): void {
    Jetstream::useUserModel(User::class);

    $team = createTeam();

    $action = new ValidateTeamDeletion;

    $action->validate($team->owner, $team);

    $this->assertTrue(true);
});

test('personal team cant be deleted', function (): void {
    $this->expectException(ValidationException::class);

    Jetstream::useUserModel(User::class);

    $team = createTeam();

    $team->forceFill(['personal_team' => true])->save();

    $action = new ValidateTeamDeletion;

    $action->validate($team->owner, $team);
});

test('non owner cant delete team', function (): void {
    $this->expectException(AuthorizationException::class);

    Jetstream::useUserModel(User::class);

    $team = createTeam();

    $action = new ValidateTeamDeletion;

    $action->validate(User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]), $team);
});

function createTeam()
{
    $action = new CreateTeam;

    $user = User::forceCreate([
        'name' => 'Taylor Otwell',
        'email' => 'taylor@laravel.com',
        'password' => 'secret',
    ]);

    return $action->create($user, ['name' => 'Test Team']);
}

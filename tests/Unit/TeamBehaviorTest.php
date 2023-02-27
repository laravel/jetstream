<?php

namespace Laravel\Jetstream\Tests\Unit;

use App\Actions\Jetstream\CreateTeam;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Team;
use Laravel\Jetstream\Tests\Fixtures\TeamPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\TransientToken;

beforeEach(function () {
    Gate::policy(\App\Models\Team::class, TeamPolicy::class);
    Jetstream::useUserModel(User::class);

    $this->artisan('migrate', ['--database' => 'testbench'])->run();
});

test('team relationship methods', function (): void {
    $action = new CreateTeam;

    $user = User::forceCreate([
        'name' => 'Taylor Otwell',
        'email' => 'taylor@laravel.com',
        'password' => 'secret',
    ]);

    $team = $action->create($user, ['name' => 'Test Team']);

    expect($team)->toBeInstanceOf(Team::class)
        ->and($user->belongsToTeam($team))->toBeTrue()
        ->and($user->ownsTeam($team))->toBeTrue()
        ->and($user->fresh()->ownedTeams)->toHaveCount(1)
        ->and($user->fresh()->allTeams())->toHaveCount(1);

    $team->forceFill(['personal_team' => true])->save();

    expect($user->fresh()->personalTeam()->id)->toEqual($team->id)
        ->and($user->fresh()->currentTeam->id)->toEqual($team->id)
        ->and($user->hasTeamPermission($team, 'foo'))->toBeTrue();

    // Test with another user that isn't on the team...
    $otherUser = User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]);

    expect($otherUser->belongsToTeam($team))->toBeFalse()
        ->and($otherUser->ownsTeam($team))->toBeFalse()
        ->and($otherUser->hasTeamPermission($team, 'foo'))->toBeFalse();

    // Add the other user to the team...
    Jetstream::role('editor', 'Editor', ['foo']);

    $otherUser->teams()->attach($team, ['role' => 'editor']);
    $otherUser = $otherUser->fresh();

    expect($otherUser->belongsToTeam($team))->toBeTrue()
        ->and($otherUser->ownsTeam($team))->toBeFalse()
        ->and($otherUser->hasTeamPermission($team, 'foo'))->toBeTrue()
        ->and($otherUser->hasTeamPermission($team, 'bar'))->toBeFalse()
        ->and($team->userHasPermission($otherUser, 'foo'))->toBeTrue()
        ->and($team->userHasPermission($otherUser, 'bar'))->toBeFalse();

    $otherUser->withAccessToken(new TransientToken);

    expect($otherUser->belongsToTeam($team))->toBeTrue()
        ->and($otherUser->ownsTeam($team))->toBeFalse()
        ->and($otherUser->hasTeamPermission($team, 'foo'))->toBeTrue()
        ->and($otherUser->hasTeamPermission($team, 'bar'))->toBeFalse()
        ->and($team->userHasPermission($otherUser, 'foo'))->toBeTrue()
        ->and($team->userHasPermission($otherUser, 'bar'))->toBeFalse();
});

test('has team permission checks token permissions', function (): void {
    Jetstream::role('admin', 'Administrator', ['foo']);

    $action = new CreateTeam;

    $user = User::forceCreate([
        'name' => 'Taylor Otwell',
        'email' => 'taylor@laravel.com',
        'password' => 'secret',
    ]);

    $team = $action->create($user, ['name' => 'Test Team']);

    $adam = User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]);

    $authToken = new Sanctum;
    $adam = $authToken->actingAs($adam, ['bar'], []);

    $team->users()->attach($adam, ['role' => 'admin']);

    expect($adam->hasTeamPermission($team, 'foo'))->toBeFalse();

    $john = User::forceCreate([
        'name' => 'John Doe',
        'email' => 'john@doe.com',
        'password' => 'secret',
    ]);

    $authToken = new Sanctum;
    $john = $authToken->actingAs($john, ['foo'], []);

    $team->users()->attach($john, ['role' => 'admin']);

    expect($john->hasTeamPermission($team, 'foo'))->toBeTrue();
});

test('user does not need to refresh after switching teams', function (): void {
    $action = new CreateTeam;

    $user = User::forceCreate([
        'name' => 'Taylor Otwell',
        'email' => 'taylor@laravel.com',
        'password' => 'secret',
    ]);

    $personalTeam = $action->create($user, ['name' => 'Personal Team']);

    $personalTeam->forceFill(['personal_team' => true])->save();

    expect($user->isCurrentTeam($personalTeam))->toBeTrue();

    $anotherTeam = $action->create($user, ['name' => 'Test Team']);

    expect($user->isCurrentTeam($anotherTeam))->toBeTrue();
});

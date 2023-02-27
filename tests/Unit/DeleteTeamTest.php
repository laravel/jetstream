<?php

namespace Laravel\Jetstream\Tests\Unit;

use App\Actions\Jetstream\DeleteTeam;
use App\Models\Team;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\TeamPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;

beforeEach(function () {
    Gate::policy(Team::class, TeamPolicy::class);
    Jetstream::useUserModel(User::class);

    $this->artisan('migrate', ['--database' => 'testbench'])->run();
});

test('team can be deleted', function (): void {
    $team = createTeam();

    $action = new DeleteTeam;

    $action->delete($team);

    expect($team->fresh())->toBeNull();
});

test('team deletion can be validated', function (): void {
    Jetstream::useUserModel(User::class);

    $team = createTeam();

    $action = new ValidateTeamDeletion;

    expect(
        fn() => $action->validate($team->owner, $team)
    )
        ->not()->toThrow(ValidationException::class)
        ->and(true)->toBeTrue();
});

test('personal team cant be deleted', function (): void {
    Jetstream::useUserModel(User::class);

    $team = createTeam();

    $team->forceFill(['personal_team' => true])->save();

    $action = new ValidateTeamDeletion;

    expect(
        fn() => $action->validate($team->owner, $team)
    )->toThrow(ValidationException::class);
});

test('non owner cant delete team', function (): void {
    Jetstream::useUserModel(User::class);

    $team = createTeam();

    $action = new ValidateTeamDeletion;

    expect(
        fn() => $action->validate(User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]), $team)
    )->toThrow(AuthorizationException::class);
});

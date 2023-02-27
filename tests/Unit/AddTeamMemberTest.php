<?php

namespace Laravel\Jetstream\Tests\Unit;

use App\Actions\Jetstream\AddTeamMember;
use App\Models\Team;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Membership;
use Laravel\Jetstream\Tests\Fixtures\TeamPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;
use Laravel\Sanctum\TransientToken;

beforeEach(function () {
    Gate::policy(Team::class, TeamPolicy::class);
    Jetstream::useUserModel(User::class);

    $this->artisan('migrate', ['--database' => 'testbench'])->run();
});

test('team members can be added', function (): void {
    Jetstream::role('admin', 'Admin', ['foo']);

    $team = createTeam();

    $otherUser = User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]);

    $action = new AddTeamMember;

    $action->add($team->owner, $team, 'adam@laravel.com', 'admin');

    $team = $team->fresh();

    expect($team->users)->toHaveCount(1)
        ->and($team->users[0]->membership)->toBeInstanceOf(Membership::class)
        ->and($otherUser->hasTeamRole($team, 'admin'))->toBeTrue()
        ->and($otherUser->hasTeamRole($team, 'editor'))->toBeFalse()
        ->and($otherUser->hasTeamRole($team, 'foobar'))->toBeFalse();

    $team->users->first()->withAccessToken(new TransientToken);

    expect($team->users->first()->hasTeamPermission($team, 'foo'))->toBeTrue()
        ->and($team->users->first()->hasTeamPermission($team, 'bar'))->toBeFalse();
});

test('user email address must exist', function (): void {
    $team = createTeam();

    $action = new AddTeamMember;

    expect(fn() => $action->add($team->owner, $team, 'missing@laravel.com', 'admin'))
        ->toThrow(ValidationException::class)
        ->and($team->fresh()->users)->toHaveCount(0);
});

test('user cant already be on team', function (): void {
    $team = createTeam();

    User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]);

    $action = new AddTeamMember;

    $action->add($team->owner, $team, 'adam@laravel.com', 'admin');

    expect(fn() => $action->add($team->owner, $team->fresh(), 'adam@laravel.com', 'admin'))
        ->toThrow(ValidationException::class);
});

<?php

namespace Laravel\Jetstream\Tests\Unit;

use App\Actions\Jetstream\RemoveTeamMember;
use App\Models\Team;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Events\RemovingTeamMember;
use Laravel\Jetstream\Events\TeamMemberRemoved;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\TeamPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;

beforeEach(function () {
    Gate::policy(Team::class, TeamPolicy::class);
    Jetstream::useUserModel(User::class);

    $this->artisan('migrate', ['--database' => 'testbench'])->run();
});

test('team members can be removed', function (): void {
    Event::fake([TeamMemberRemoved::class]);

    $team = createTeam();

    $otherUser = User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]);

    $team->users()->attach($otherUser, ['role' => null]);

    expect($team->fresh()->users)->toHaveCount(1);

    Auth::login($team->owner);

    $action = new RemoveTeamMember;

    $action->remove($team->owner, $team, $otherUser);

    expect($team->fresh()->users)->toHaveCount(0);

    Event::assertDispatched(TeamMemberRemoved::class);
});

test('a team owner cant remove themselves', function (): void {
    Event::fake([RemovingTeamMember::class]);

    $team = createTeam();

    Auth::login($team->owner);

    $action = new RemoveTeamMember;

    expect(
        fn () => $action->remove($team->owner, $team, $team->owner)
    )->toThrow(ValidationException::class);
});

test('the user must be authorized to remove team members', function (): void {
    $team = createTeam();

    $adam = User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]);

    $abigail = User::forceCreate([
        'name' => 'Abigail Otwell',
        'email' => 'abigail@laravel.com',
        'password' => 'secret',
    ]);

    $team->users()->attach($adam, ['role' => null]);
    $team->users()->attach($abigail, ['role' => null]);

    Auth::login($team->owner);

    $action = new RemoveTeamMember;

    expect(
        fn () => $action->remove($adam, $team, $abigail)
    )->toThrow(AuthorizationException::class);
});

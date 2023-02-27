<?php

namespace Laravel\Jetstream\Tests\Unit;

use App\Actions\Jetstream\InviteTeamMember;
use App\Models\Team;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\TeamPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;

beforeEach(function () {
    Gate::policy(Team::class, TeamPolicy::class);
    Jetstream::useUserModel(User::class);

    $this->artisan('migrate', ['--database' => 'testbench'])->run();
});

test('team members can be invited', function (): void {
    Mail::fake();

    Jetstream::role('admin', 'Admin', ['foo']);

    $team = createTeam();

    User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]);

    $action = new InviteTeamMember;

    $action->invite($team->owner, $team, 'adam@laravel.com', 'admin');

    $team = $team->fresh();

    expect($team->users)->toHaveCount(0)
        ->and($team->teamInvitations)->toHaveCount(1)
        ->and($team->teamInvitations->first()->email)->toEqual('adam@laravel.com')
        ->and($team->teamInvitations->first()->team->id)->toEqual($team->id);
});

test('user cant already be on team', function (): void {
    Mail::fake();

    $team = createTeam();

    User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]);

    $action = new InviteTeamMember;

    $action->invite($team->owner, $team, 'adam@laravel.com', 'admin');

    expect(
        fn () => $action->invite($team->owner, $team->fresh(), 'adam@laravel.com', 'admin')
    )->toThrow(ValidationException::class);
});

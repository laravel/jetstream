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

    $otherUser = User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]);

    $action = new InviteTeamMember;

    $action->invite($team->owner, $team, 'adam@laravel.com', 'admin');

    $team = $team->fresh();

    $this->assertCount(0, $team->users);
    $this->assertCount(1, $team->teamInvitations);
    $this->assertEquals('adam@laravel.com', $team->teamInvitations->first()->email);
    $this->assertEquals($team->id, $team->teamInvitations->first()->team->id);
});

test('user cant already be on team', function (): void  {
    Mail::fake();

    $this->expectException(ValidationException::class);

    $team = createTeam();

    $otherUser = User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]);

    $action = new InviteTeamMember;

    $action->invite($team->owner, $team, 'adam@laravel.com', 'admin');
    $this->assertTrue(true);
    $action->invite($team->owner, $team->fresh(), 'adam@laravel.com', 'admin');
});

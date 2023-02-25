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

    $this->assertCount(1, $team->users);

    $this->assertInstanceOf(Membership::class, $team->users[0]->membership);

    $this->assertTrue($otherUser->hasTeamRole($team, 'admin'));
    $this->assertFalse($otherUser->hasTeamRole($team, 'editor'));
    $this->assertFalse($otherUser->hasTeamRole($team, 'foobar'));

    $team->users->first()->withAccessToken(new TransientToken);

    $this->assertTrue($team->users->first()->hasTeamPermission($team, 'foo'));
    $this->assertFalse($team->users->first()->hasTeamPermission($team, 'bar'));
});

test('user email address must exist', function (): void {
    $this->expectException(ValidationException::class);

    $team = createTeam();

    $action = new AddTeamMember;

    $action->add($team->owner, $team, 'missing@laravel.com', 'admin');

    $this->assertCount(1, $team->fresh()->users);
});

test('user cant already be on team', function (): void {
    $this->expectException(ValidationException::class);

    $team = createTeam();

    $otherUser = User::forceCreate([
        'name' => 'Adam Wathan',
        'email' => 'adam@laravel.com',
        'password' => 'secret',
    ]);

    $action = new AddTeamMember;

    $action->add($team->owner, $team, 'adam@laravel.com', 'admin');
    $this->assertTrue(true);
    $action->add($team->owner, $team->fresh(), 'adam@laravel.com', 'admin');
});

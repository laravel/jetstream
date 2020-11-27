<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\CreateTeam;
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

class RemoveTeamMemberTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Team::class, TeamPolicy::class);

        Jetstream::useUserModel(User::class);
    }

    public function test_team_members_can_be_removed()
    {
        Event::fake([TeamMemberRemoved::class]);

        $this->migrate();

        $team = $this->createTeam();

        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $team->users()->attach($otherUser, ['role' => null]);

        $this->assertCount(1, $team->fresh()->users);

        Auth::login($team->owner);

        $action = new RemoveTeamMember;

        $action->remove($team->owner, $team, $otherUser);

        $this->assertCount(0, $team->fresh()->users);

        Event::assertDispatched(TeamMemberRemoved::class);
    }

    public function test_a_team_owner_cant_remove_themselves()
    {
        $this->expectException(ValidationException::class);

        Event::fake([RemovingTeamMember::class]);

        $this->migrate();

        $team = $this->createTeam();

        Auth::login($team->owner);

        $action = new RemoveTeamMember;

        $action->remove($team->owner, $team, $team->owner);
    }

    public function test_the_user_must_be_authorized_to_remove_team_members()
    {
        $this->expectException(AuthorizationException::class);

        $this->migrate();

        $team = $this->createTeam();

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

        $action->remove($adam, $team, $abigail);
    }

    protected function createTeam()
    {
        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        return $action->create($user, ['name' => 'Test Team']);
    }

    protected function migrate()
    {
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

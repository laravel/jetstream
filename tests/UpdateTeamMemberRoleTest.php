<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\AddTeamMember;
use App\Actions\Jetstream\CreateTeam;
use App\Models\Team;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Actions\UpdateTeamMemberRole;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Membership;
use Laravel\Jetstream\Tests\Fixtures\TeamPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;
use Laravel\Sanctum\TransientToken;

class UpdateTeamMemberRoleTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Team::class, TeamPolicy::class);
        Jetstream::useUserModel(User::class);
    }

    public function test_team_members_can_be_updated()
    {
        Jetstream::role('admin', 'Admin', ['foo']);
        Jetstream::role('admin2', 'Admin2', ['foo2']);
        Jetstream::role('admin3', 'Admin3', ['foo3']);

        $this->migrate();

        $team = $this->createTeam();

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
        $this->assertFalse($otherUser->hasTeamRole($team, 'admin2'));
        $this->assertFalse($otherUser->hasTeamRole($team, 'admin3'));

        $team->users->first()->withAccessToken(new TransientToken);

        $this->assertTrue($team->users->first()->hasTeamPermission($team, 'foo'));
        $this->assertFalse($team->users->first()->hasTeamPermission($team, 'foo2'));
        $this->assertFalse($team->users->first()->hasTeamPermission($team, 'foo3'));

        $action = new UpdateTeamMemberRole();
        $action->update($team->owner, $team, $otherUser->id, ['admin2', 'admin3']);

        $team = $team->fresh();

        $this->assertFalse($otherUser->hasTeamRole($team, 'admin'));
        $this->assertTrue($otherUser->hasTeamRole($team, 'admin2'));
        $this->assertTrue($otherUser->hasTeamRole($team, 'admin3'));

        $this->assertFalse($team->users->first()->hasTeamPermission($team, 'foo'));
        $this->assertTrue($team->users->first()->hasTeamPermission($team, 'foo2'));
        $this->assertTrue($team->users->first()->hasTeamPermission($team, 'foo3'));
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
        // $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

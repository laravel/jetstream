<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\CreateTeam;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Team;
use Laravel\Jetstream\Tests\Fixtures\User;
use Laravel\Sanctum\TransientToken;

class TeamBehaviorTest extends OrchestraTestCase
{
    public function test_team_relationship_methods()
    {
        $this->migrate();

        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $team = $action->create($user, ['name' => 'Test Team']);

        $this->assertInstanceOf(Team::class, $team);

        $this->assertTrue($user->belongsToTeam($team));
        $this->assertTrue($user->ownsTeam($team));
        $this->assertCount(1, $user->fresh()->ownedTeams);
        $this->assertCount(1, $user->fresh()->allTeams());

        $team->forceFill(['personal_team' => true])->save();

        $this->assertEquals($team->id, $user->fresh()->personalTeam()->id);
        $this->assertEquals($team->id, $user->fresh()->currentTeam->id);
        $this->assertTrue($user->hasTeamPermission($team, 'foo'));

        // Test with another user that isn't on the team...
        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $this->assertFalse($otherUser->belongsToTeam($team));
        $this->assertFalse($otherUser->ownsTeam($team));
        $this->assertFalse($otherUser->hasTeamPermission($team, 'foo'));

        // Add the other user to the team...
        Jetstream::role('editor', 'Editor', ['foo']);

        $otherUser->teams()->attach($team, ['role' => 'editor']);
        $otherUser = $otherUser->fresh();

        $otherUser->withAccessToken(new TransientToken);

        $this->assertTrue($otherUser->belongsToTeam($team));
        $this->assertFalse($otherUser->ownsTeam($team));

        $this->assertTrue($otherUser->hasTeamPermission($team, 'foo'));
        $this->assertFalse($otherUser->hasTeamPermission($team, 'bar'));

        $this->assertTrue($team->userHasPermission($otherUser, 'foo'));
        $this->assertFalse($team->userHasPermission($otherUser, 'bar'));
    }

    public function test_has_team_permission_checks_token_permissions()
    {
        Jetstream::role('admin', 'Administrator', ['foo']);

        $this->migrate();

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

        $team->users()->attach($adam, ['role' => 'admin']);

        $this->assertFalse($adam->hasTeamPermission($team, 'foo'));
    }

    protected function migrate()
    {
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

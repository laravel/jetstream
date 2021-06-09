<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateTeamNameTest extends TestCase
{
    use RefreshDatabase;

    public function test_teams_api_validates_input()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $this->put('/teams/'.$user->currentTeam->id)
            ->assertSessionHasErrors(['name'], null, 'updateTeamName');
    }

    public function test_teams_api_validates_team_ownership()
    {
        $user1 = User::factory()->withPersonalTeam()->create();
        $user2 = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user1);

        $this->put('/teams/'.$user2->currentTeam->id)->assertForbidden();
    }

    public function test_team_names_can_be_updated()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $this->put('/teams/'.$user->currentTeam->id, [
            'name' => 'Test Team',
        ]);

        $this->assertCount(1, $user->fresh()->ownedTeams);
        $this->assertEquals('Test Team', $user->currentTeam->fresh()->name);
    }
}

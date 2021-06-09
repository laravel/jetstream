<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTeamTest extends TestCase
{
    use RefreshDatabase;

    public function test_teams_api_validates_input()
    {
        $this->actingAs(User::factory()->withPersonalTeam()->create());

        $this->post('/teams')->assertSessionHasErrors(['name'], null, 'createTeam');
    }

    public function test_teams_can_be_created()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $response = $this->post('/teams', [
            'name' => 'Test Team',
        ]);

        $ownedTeams = $user->fresh()->ownedTeams()->latest('id')->get();

        $this->assertCount(2, $ownedTeams);
        $this->assertEquals('Test Team', $ownedTeams->first()->name);
        $this->assertFalse($ownedTeams->first()->personal_team);
    }
}

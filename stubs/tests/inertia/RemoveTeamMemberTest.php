<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class RemoveTeamMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_members_can_be_removed_from_teams()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->markTestSkipped('Teams support is not enabled.');
        }

        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id);

        $this->assertCount(0, $user->currentTeam->fresh()->users);
    }

    public function test_only_team_owner_can_remove_team_members()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->markTestSkipped('Teams support is not enabled.');
        }

        $user = User::factory()->withPersonalTeam()->create();

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $this->actingAs($otherUser);

        $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$user->id);

        $response->assertStatus(403);
    }
}

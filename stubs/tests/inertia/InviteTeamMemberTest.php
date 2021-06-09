<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Mail\TeamInvitation;
use Tests\TestCase;

class InviteTeamMemberTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_members_api_validates_input()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $this->post('/teams/'.$user->currentTeam->id.'/members')
            ->assertSessionHasErrors(['email', 'role'], null, 'addTeamMember');
    }

    public function test_team_members_api_validates_team_ownership()
    {
        $user1 = User::factory()->withPersonalTeam()->create();
        $user2 = User::factory()->withPersonalTeam()->create();

        $this->actingAs($user1);

        $this->post('/teams/'.$user2->currentTeam->id.'/members')->assertForbidden();
    }

    public function test_team_members_can_be_invited_to_team()
    {
        Mail::fake();

        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $response = $this->post('/teams/'.$user->currentTeam->id.'/members', [
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        Mail::assertSent(TeamInvitation::class);

        $this->assertCount(1, $user->currentTeam->fresh()->teamInvitations);
    }

    public function test_team_member_invitations_can_be_cancelled()
    {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $invitation = $user->currentTeam->teamInvitations()->create([
            'email' => 'test@example.com',
            'role' => 'admin',
        ]);

        $response = $this->delete('/team-invitations/'.$invitation->id);

        $this->assertCount(0, $user->currentTeam->fresh()->teamInvitations);
    }
}

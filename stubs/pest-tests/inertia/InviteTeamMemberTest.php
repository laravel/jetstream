<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Mail\TeamInvitation;

test('team members can be invited to team', function () {
    Mail::fake();

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $response = $this->post('/teams/'.$user->currentTeam->id.'/members', [
        'email' => 'test@example.com',
        'role' => 'admin',
    ]);

    Mail::assertSent(TeamInvitation::class);

    $this->assertCount(1, $user->currentTeam->fresh()->teamInvitations);
});

test('team member invitations can be cancelled', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $invitation = $user->currentTeam->teamInvitations()->create([
        'email' => 'test@example.com',
        'role' => 'admin',
    ]);

    $response = $this->delete('/team-invitations/'.$invitation->id);

    $this->assertCount(0, $user->currentTeam->fresh()->teamInvitations);
});

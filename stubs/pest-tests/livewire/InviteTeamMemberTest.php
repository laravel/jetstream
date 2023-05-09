<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Laravel\Jetstream\Mail\TeamInvitation;
use Livewire\Livewire;

test('team members can be invited to team', function () {
    Mail::fake();

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
        ->set('addTeamMemberForm', [
            'email' => 'test@example.com',
            'role' => 'admin',
        ])->call('addTeamMember');

    Mail::assertSent(TeamInvitation::class);

    expect($user->currentTeam->fresh()->teamInvitations)->toHaveCount(1);
})->skip(function () {
    return ! Features::sendsTeamInvitations();
}, 'Team invitations not enabled.');

test('team member invitations can be cancelled', function () {
    Mail::fake();

    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    // Add the team member...
    $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
        ->set('addTeamMemberForm', [
            'email' => 'test@example.com',
            'role' => 'admin',
        ])->call('addTeamMember');

    $invitationId = $user->currentTeam->fresh()->teamInvitations->first()->id;

    // Cancel the team invitation...
    $component->call('cancelTeamInvitation', $invitationId);

    expect($user->currentTeam->fresh()->teamInvitations)->toHaveCount(0);
})->skip(function () {
    return ! Features::sendsTeamInvitations();
}, 'Team invitations not enabled.');

<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Livewire\Livewire;

test('team members can be removed from teams', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                    ->set('teamMemberIdBeingRemoved', $otherUser->id)
                    ->call('removeTeamMember');

    expect($user->currentTeam->fresh()->users)->toHaveCount(0);
});

test('only team owner can remove team members', function () {
    $user = User::factory()->withPersonalTeam()->create();

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                    ->set('teamMemberIdBeingRemoved', $user->id)
                    ->call('removeTeamMember')
                    ->assertStatus(403);
});

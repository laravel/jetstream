<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Livewire\Livewire;

test('team member roles can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                    ->set('managingRoleFor', $otherUser)
                    ->set('currentRole', 'editor')
                    ->call('updateRole');

    $this->assertTrue($otherUser->fresh()->hasTeamRole(
        $user->currentTeam->fresh(), 'editor'
    ));
});

test('only team owner can update team member roles', function () {
    $user = User::factory()->withPersonalTeam()->create();

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                    ->set('managingRoleFor', $otherUser)
                    ->set('currentRole', 'editor')
                    ->call('updateRole')
                    ->assertStatus(403);

    $this->assertTrue($otherUser->fresh()->hasTeamRole(
        $user->currentTeam->fresh(), 'admin'
    ));
});

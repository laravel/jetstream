<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Livewire\Livewire;

test('users can leave teams', function () {
    $user = User::factory()->withPersonalTeam()->create();

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                    ->call('leaveTeam');

    $this->assertCount(0, $user->currentTeam->fresh()->users);
});

test('team owners cant leave their own team', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                    ->call('leaveTeam')
                    ->assertHasErrors(['team']);

    $this->assertNotNull($user->currentTeam->fresh());
});

<?php

use App\Models\Team;
use App\Models\User;
use Laravel\Jetstream\Http\Livewire\DeleteTeamForm;
use Livewire\Livewire;

test('teams can be deleted', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $user->ownedTeams()->save($team = Team::factory()->make([
        'personal_team' => false,
    ]));

    $team->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'test-role']
    );

    $component = Livewire::test(DeleteTeamForm::class, ['team' => $team->fresh()])
        ->call('deleteTeam');

    expect($team->fresh())->toBeNull();
    expect($otherUser->fresh()->teams)->toHaveCount(0);
});

test('personal teams cant be deleted', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $component = Livewire::test(DeleteTeamForm::class, ['team' => $user->currentTeam])
        ->call('deleteTeam')
        ->assertHasErrors(['team']);

    expect($user->currentTeam->fresh())->not->toBeNull();
});

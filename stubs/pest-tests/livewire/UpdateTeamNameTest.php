<?php

use App\Models\User;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\UpdateTeamNameForm;
use Livewire\Livewire;

test('team names can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    Livewire::test(UpdateTeamNameForm::class, ['team' => $user->currentTeam])
                ->set(['state' => ['name' => 'Test Team']])
                ->call('updateTeamName');

    expect($user->fresh()->ownedTeams)->toHaveCount(1);
    expect($user->currentTeam->fresh()->name)->toEqual('Test Team');
})->skip(function () {
    return ! Features::hasTeamFeatures();
}, 'Team features are not enabled.');

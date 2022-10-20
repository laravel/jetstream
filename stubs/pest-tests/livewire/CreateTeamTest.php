<?php

use App\Models\User;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\CreateTeamForm;
use Livewire\Livewire;

test('teams can be created', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    Livewire::test(CreateTeamForm::class)
                ->set(['state' => ['name' => 'Test Team']])
                ->call('createTeam');

    expect($user->fresh()->ownedTeams)->toHaveCount(2);
    expect($user->fresh()->ownedTeams()->latest('id')->first()->name)->toEqual('Test Team');
})->skip(function () {
    return ! Features::hasTeamFeatures();
}, 'Team features are not enabled.');

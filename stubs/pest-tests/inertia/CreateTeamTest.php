<?php

use App\Models\User;

test('teams can be created', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $response = $this->post('/teams', [
        'name' => 'Test Team',
    ]);

    expect($user->fresh()->ownedTeams)->toHaveCount(2);
    expect($user->fresh()->ownedTeams()->get()->some(function($team) { return $team->name === 'Test Team'; }))
        ->toEqual(TRUE);
});

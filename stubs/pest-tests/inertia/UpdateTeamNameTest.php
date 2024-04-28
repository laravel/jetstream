<?php

use App\Models\User;

test('team names can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $this->put('/teams/'.$user->currentTeam->id, [
        'name' => 'Test Team',
    ]);

    expect($user->fresh()->ownedTeams)->toHaveCount(1);
    expect($user->currentTeam->fresh()->name)->toEqual('Test Team');
});

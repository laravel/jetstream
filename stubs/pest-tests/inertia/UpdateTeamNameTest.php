<?php

use App\Models\User;

test('team names can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $response = $this->put('/teams/'.$user->currentTeam->id, [
        'name' => 'Test Team',
    ]);

    $this->assertCount(1, $user->fresh()->ownedTeams);
    $this->assertEquals('Test Team', $user->currentTeam->fresh()->name);
});

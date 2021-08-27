<?php

use App\Models\User;

test('teams can be created', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $response = $this->post('/teams', [
        'name' => 'Test Team',
    ]);

    $this->assertCount(2, $user->fresh()->ownedTeams);
    $this->assertEquals('Test Team', $user->fresh()->ownedTeams()->latest('id')->first()->name);
});

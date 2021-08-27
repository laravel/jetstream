<?php

use App\Models\User;

test('users can leave teams', function () {
    $user = User::factory()->withPersonalTeam()->create();

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id);

    $this->assertCount(0, $user->currentTeam->fresh()->users);
});

test('team owners cant leave their own team', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$user->id);

    $response->assertSessionHasErrorsIn('removeTeamMember', ['team']);

    $this->assertNotNull($user->currentTeam->fresh());
});

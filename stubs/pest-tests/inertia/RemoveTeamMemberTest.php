<?php

use App\Models\User;
use Laravel\Jetstream\Features;

test('team members can be removed from teams', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id);

    expect($user->currentTeam->fresh()->users)->toHaveCount(0);
})->skip(function () {
    return ! Features::hasTeamFeatures();
}, 'Team features are not enabled.');

test('only team owner can remove team members', function () {
    $user = User::factory()->withPersonalTeam()->create();

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$user->id);

    $response->assertStatus(403);
})->skip(function () {
    return ! Features::hasTeamFeatures();
}, 'Team features are not enabled.');

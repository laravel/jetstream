<?php

use App\Models\User;
use Laravel\Jetstream\Features;

test('users can leave teams', function () {
    $user = User::factory()->withPersonalTeam()->create();

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id);

    expect($user->currentTeam->fresh()->users)->toHaveCount(0);
})->skip(function () {
    return ! Features::hasTeamFeatures();
}, 'Team features are not enabled.');

test('team owners cant leave their own team', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $response = $this->delete('/teams/'.$user->currentTeam->id.'/members/'.$user->id);

    $response->assertSessionHasErrorsIn('removeTeamMember', ['team']);

    expect($user->currentTeam->fresh())->not->toBeNull();
})->skip(function () {
    return ! Features::hasTeamFeatures();
}, 'Team features are not enabled.');

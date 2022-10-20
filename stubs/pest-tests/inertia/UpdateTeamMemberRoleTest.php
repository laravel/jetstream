<?php

use App\Models\User;
use Laravel\Jetstream\Features;

test('team member roles can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $response = $this->put('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id, [
        'role' => 'editor',
    ]);

    expect($otherUser->fresh()->hasTeamRole(
        $user->currentTeam->fresh(), 'editor'
    ))->toBeTrue();
})->skip(function () {
    return ! Features::hasTeamFeatures();
}, 'Team features are not enabled.');

test('only team owner can update team member roles', function () {
    $user = User::factory()->withPersonalTeam()->create();

    $user->currentTeam->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $response = $this->put('/teams/'.$user->currentTeam->id.'/members/'.$otherUser->id, [
        'role' => 'editor',
    ]);

    expect($otherUser->fresh()->hasTeamRole(
        $user->currentTeam->fresh(), 'admin'
    ))->toBeTrue();
})->skip(function () {
    return ! Features::hasTeamFeatures();
}, 'Team features are not enabled.');

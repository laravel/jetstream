<?php

use App\Models\User;

test('company employee roles can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $user->currentCompany->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $response = $this->put('/companies/'.$user->currentCompany->id.'/employees/'.$otherUser->id, [
        'role' => 'editor',
    ]);

    expect($otherUser->fresh()->hasCompanyRole(
        $user->currentCompany->fresh(), 'editor'
    ))->toBeTrue();
});

test('only company owner can update company employee roles', function () {
    $user = User::factory()->withPersonalCompany()->create();

    $user->currentCompany->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $response = $this->put('/companies/'.$user->currentCompany->id.'/employees/'.$otherUser->id, [
        'role' => 'editor',
    ]);

    expect($otherUser->fresh()->hasCompanyRole(
        $user->currentCompany->fresh(), 'admin'
    ))->toBeTrue();
});

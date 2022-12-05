<?php

use App\Models\User;

test('company employees can be removed from companies', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $user->currentCompany->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $response = $this->delete('/companies/'.$user->currentCompany->id.'/employees/'.$otherUser->id);

    expect($user->currentCompany->fresh()->users)->toHaveCount(0);
});

test('only company owner can remove company employees', function () {
    $user = User::factory()->withPersonalCompany()->create();

    $user->currentCompany->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $response = $this->delete('/companies/'.$user->currentCompany->id.'/employees/'.$user->id);

    $response->assertStatus(403);
});

<?php

use App\Models\User;

test('users can leave companies', function () {
    $user = User::factory()->withPersonalCompany()->create();

    $user->currentCompany->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $response = $this->delete('/companies/'.$user->currentCompany->id.'/employees/'.$otherUser->id);

    expect($user->currentCompany->fresh()->users)->toHaveCount(0);
});

test('company owners cant leave their own company', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $response = $this->delete('/companies/'.$user->currentCompany->id.'/employees/'.$user->id);

    $response->assertSessionHasErrorsIn('removeCompanyEmployee', ['company']);

    expect($user->currentCompany->fresh())->not->toBeNull();
});

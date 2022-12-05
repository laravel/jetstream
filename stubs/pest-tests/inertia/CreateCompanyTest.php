<?php

use App\Models\User;

test('companies can be created', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $response = $this->post('/companies', [
        'name' => 'Test Company',
    ]);

    expect($user->fresh()->ownedCompanies)->toHaveCount(2);
    expect($user->fresh()->ownedCompanies()->latest('id')->first()->name)->toEqual('Test Company');
});

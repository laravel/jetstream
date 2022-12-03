<?php

use App\Models\User;

test('company names can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $response = $this->put('/companies/'.$user->currentCompany->id, [
        'name' => 'Test Company',
    ]);

    expect($user->fresh()->ownedCompanies)->toHaveCount(1);
    expect($user->currentCompany->fresh()->name)->toEqual('Test Company');
});

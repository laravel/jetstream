<?php

use App\Models\User;
use Laravel\Fortify\Features;

test('profile information can be updated', function () {
    $this->actingAs($user = User::factory()->create());

    $response = $this->put('/user/profile-information', [
        'name' => 'Test Name',
        'email' => 'test@example.com',
    ]);

    expect($user->fresh())
        ->name->toEqual('Test Name')
        ->email->toEqual('test@example.com');
})->skip(function () {
    return ! Features::enabled(Features::updateProfileInformation());
}, 'Update profile information is not enabled.');

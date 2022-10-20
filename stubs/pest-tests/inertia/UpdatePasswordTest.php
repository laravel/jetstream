<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Laravel\Fortify\Features;

test('password can be updated', function () {
    $this->actingAs($user = User::factory()->create());

    $response = $this->put('/user/password', [
        'current_password' => 'password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    expect(Hash::check('new-password', $user->fresh()->password))->toBeTrue();
})->skip(function () {
    return ! Features::enabled(Features::updatePasswords());
}, 'Password updates are not enabled.');

test('current password must be correct', function () {
    $this->actingAs($user = User::factory()->create());

    $response = $this->put('/user/password', [
        'current_password' => 'wrong-password',
        'password' => 'new-password',
        'password_confirmation' => 'new-password',
    ]);

    $response->assertSessionHasErrors();

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
})->skip(function () {
    return ! Features::enabled(Features::updatePasswords());
}, 'Password updates are not enabled.');

test('new passwords must match', function () {
    $this->actingAs($user = User::factory()->create());

    $response = $this->put('/user/password', [
        'current_password' => 'password',
        'password' => 'new-password',
        'password_confirmation' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors();

    expect(Hash::check('password', $user->fresh()->password))->toBeTrue();
})->skip(function () {
    return ! Features::enabled(Features::updatePasswords());
}, 'Password updates are not enabled.');

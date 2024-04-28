<?php

use App\Models\User;
use Laravel\Jetstream\Features;

test('user accounts can be deleted', function () {
    $this->actingAs($user = User::factory()->create());

    $this->delete('/user', [
        'password' => 'password',
    ]);

    expect($user->fresh())->toBeNull();
})->skip(function () {
    return ! Features::hasAccountDeletionFeatures();
}, 'Account deletion is not enabled.');

test('correct password must be provided before account can be deleted', function () {
    $this->actingAs($user = User::factory()->create());

    $this->delete('/user', [
        'password' => 'wrong-password',
    ]);

    expect($user->fresh())->not->toBeNull();
})->skip(function () {
    return ! Features::hasAccountDeletionFeatures();
}, 'Account deletion is not enabled.');

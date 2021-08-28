<?php

use App\Models\User;
use Laravel\Jetstream\Features;

test('user accounts can be deleted', function () {
    if (! Features::hasAccountDeletionFeatures()) {
        return $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    $response = $this->delete('/user', [
        'password' => 'password',
    ]);

    expect($user->fresh())->toBeNull();
});

test('correct password must be provided before account can be deleted', function () {
    if (! Features::hasAccountDeletionFeatures()) {
        return $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    $response = $this->delete('/user', [
        'password' => 'wrong-password',
    ]);

    expect($user->fresh())->not->toBeNull();
});

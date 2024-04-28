<?php

use App\Models\User;
use Laravel\Fortify\Features;

test('two factor authentication can be enabled', function () {
    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $this->post('/user/two-factor-authentication');

    expect($user->fresh()->two_factor_secret)->not->toBeNull();
    expect($user->fresh()->recoveryCodes())->toHaveCount(8);
})->skip(function () {
    return ! Features::canManageTwoFactorAuthentication();
}, 'Two factor authentication is not enabled.');

test('recovery codes can be regenerated', function () {
    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $this->post('/user/two-factor-authentication');
    $this->post('/user/two-factor-recovery-codes');

    $user = $user->fresh();

    $this->post('/user/two-factor-recovery-codes');

    expect($user->recoveryCodes())->toHaveCount(8);
    expect(array_diff($user->recoveryCodes(), $user->fresh()->recoveryCodes()))->toHaveCount(8);
})->skip(function () {
    return ! Features::canManageTwoFactorAuthentication();
}, 'Two factor authentication is not enabled.');

test('two factor authentication can be disabled', function () {
    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $this->post('/user/two-factor-authentication');

    $this->assertNotNull($user->fresh()->two_factor_secret);

    $this->delete('/user/two-factor-authentication');

    expect($user->fresh()->two_factor_secret)->toBeNull();
})->skip(function () {
    return ! Features::canManageTwoFactorAuthentication();
}, 'Two factor authentication is not enabled.');

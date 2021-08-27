<?php

use App\Models\User;

test('two factor authentication can be enabled', function () {
    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $response = $this->post('/user/two-factor-authentication');

    $this->assertNotNull($user->fresh()->two_factor_secret);
    $this->assertCount(8, $user->fresh()->recoveryCodes());
});

test('recovery codes can be regenerated', function () {
    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $this->post('/user/two-factor-authentication');
    $this->post('/user/two-factor-recovery-codes');

    $user = $user->fresh();

    $this->post('/user/two-factor-recovery-codes');

    $this->assertCount(8, $user->recoveryCodes());
    $this->assertCount(8, array_diff($user->recoveryCodes(), $user->fresh()->recoveryCodes()));
});

test('two factor authentication can be disabled', function () {
    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $this->post('/user/two-factor-authentication');

    $this->assertNotNull($user->fresh()->two_factor_secret);

    $this->delete('/user/two-factor-authentication');

    $this->assertNull($user->fresh()->two_factor_secret);
});

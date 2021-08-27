<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\TwoFactorAuthenticationForm;
use Livewire\Livewire;

test('two factor authentication can be enabled', function () {
    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    Livewire::test(TwoFactorAuthenticationForm::class)
            ->call('enableTwoFactorAuthentication');

    $user = $user->fresh();

    $this->assertNotNull($user->two_factor_secret);
    $this->assertCount(8, $user->recoveryCodes());
});

test('recovery codes can be regenerated', function () {
    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $component = Livewire::test(TwoFactorAuthenticationForm::class)
            ->call('enableTwoFactorAuthentication')
            ->call('regenerateRecoveryCodes');

    $user = $user->fresh();

    $component->call('regenerateRecoveryCodes');

    $this->assertCount(8, $user->recoveryCodes());
    $this->assertCount(8, array_diff($user->recoveryCodes(), $user->fresh()->recoveryCodes()));
});

test('two factor authentication can be disabled', function () {
    $this->actingAs($user = User::factory()->create());

    $this->withSession(['auth.password_confirmed_at' => time()]);

    $component = Livewire::test(TwoFactorAuthenticationForm::class)
            ->call('enableTwoFactorAuthentication');

    $this->assertNotNull($user->fresh()->two_factor_secret);

    $component->call('disableTwoFactorAuthentication');

    $this->assertNull($user->fresh()->two_factor_secret);
});

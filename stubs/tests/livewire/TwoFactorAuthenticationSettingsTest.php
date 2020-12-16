<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\TwoFactorAuthenticationForm;
use Livewire\Livewire;
use Tests\TestCase;

class TwoFactorAuthenticationSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_authentication_can_be_enabled()
    {
        $this->actingAs($user = User::factory()->create());

        $this->withSession(['auth.password_confirmed_at' => time()]);

        Livewire::test(TwoFactorAuthenticationForm::class)
                ->call('enableTwoFactorAuthentication');

        $user = $user->fresh();

        $this->assertNotNull($user->two_factor_secret);
        $this->assertCount(8, $user->recoveryCodes());
    }

    public function test_recovery_codes_can_be_regenerated()
    {
        $this->actingAs($user = User::factory()->create());

        $this->withSession(['auth.password_confirmed_at' => time()]);

        $component = Livewire::test(TwoFactorAuthenticationForm::class)
                ->call('enableTwoFactorAuthentication')
                ->call('regenerateRecoveryCodes');

        $user = $user->fresh();

        $component->call('regenerateRecoveryCodes');

        $this->assertCount(8, $user->recoveryCodes());
        $this->assertCount(8, array_diff($user->recoveryCodes(), $user->fresh()->recoveryCodes()));
    }

    public function test_two_factor_authentication_can_be_disabled()
    {
        $this->actingAs($user = User::factory()->create());

        $this->withSession(['auth.password_confirmed_at' => time()]);

        $component = Livewire::test(TwoFactorAuthenticationForm::class)
                ->call('enableTwoFactorAuthentication');

        $this->assertNotNull($user->fresh()->two_factor_secret);

        $component->call('disableTwoFactorAuthentication');

        $this->assertNull($user->fresh()->two_factor_secret);
    }
}

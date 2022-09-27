<?php

namespace Tests\Feature;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Tests\TestCase;

class TwoFactorAuthenticationNeverFinishedConfirmingTest extends TestCase
{
    use RefreshDatabase;

    public function test_two_factor_confirmation_is_reset_on_page_reload()
    {
        if (! Features::canManageTwoFactorAuthentication()) {
            return $this->markTestSkipped('Two factor authentication is not enabled.');
        }

        if (! Features::optionEnabled('two-factor-authentication', 'confirm')) {
            return $this->markTestSkipped('Two factor confirm is not enabled.');
        }

        $this->actingAs($user = User::factory()->create());

        $timestamp = time();

        //confirm totally disabled two factor auth
        $this->get('/user/profile')
            ->assertSessionHas('two_factor_empty_at', $timestamp);

        $this->withSession(['auth.password_confirmed_at' => $timestamp]);
        $this->post('/user/two-factor-authentication');

        //confirm it was previously totally disabled but is now confirming
        $this->get('/user/profile')
            ->assertSessionHas('two_factor_confirming_at', $timestamp);

        $this->assertNotNull($user->fresh()->two_factor_secret);
        $this->assertNull($user->fresh()->two_factor_confirmed_at);

        //let's assume the user fired a page reload after one second
        $this->withSession(['two_factor_confirming_at' => Carbon::now()->subSecond(1)->unix()]);

        $this->get('/user/profile');

        $this->assertNull($user->fresh()->two_factor_secret);
    }
}
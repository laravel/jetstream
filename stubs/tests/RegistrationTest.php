<?php

namespace Tests\Feature;

use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\Features;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;
use Tests\TestCase;

class RegistrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_screen_can_be_rendered()
    {
        if (! Features::enabled(Features::registration())) {
            return $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(200);
    }

    public function test_registration_screen_cannot_be_rendered_if_support_is_disabled()
    {
        if (Features::enabled(Features::registration())) {
            return $this->markTestSkipped('Registration support is enabled.');
        }

        $response = $this->get('/register');

        $response->assertStatus(404);
    }

    public function test_register_api_validates_input()
    {
        $response = $this->post('/register');

        $keys = ['name', 'email', 'password'];

        if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
            $keys[] = 'terms';
        }

        $response->assertSessionHasErrors($keys);
    }

    public function test_new_users_can_register()
    {
        if (! Features::enabled(Features::registration())) {
            return $this->markTestSkipped('Registration support is not enabled.');
        }

        $response = $this->post('/register', [
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => 'password',
            'password_confirmation' => 'password',
            'terms' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(RouteServiceProvider::HOME);

        if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
            $personalTeam = Auth::user()->ownedTeams()->first();

            $this->assertNotNull($personalTeam, 'User personal team is not created.');
            $this->assertEquals("Test's Team", $personalTeam->name);
            $this->assertTrue($personalTeam->personal_team);
        }
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class UpdatePasswordTest extends TestCase
{
    use RefreshDatabase;

    public function test_password_can_be_updated()
    {
        $user = Features::hasTeamFeatures()
            ? User::factory()->withPersonalTeam()->create()
            : User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('/user/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $this->assertTrue(Hash::check('new-password', $user->fresh()->password));
    }

    public function test_current_password_must_be_correct()
    {
        $user = Features::hasTeamFeatures()
            ? User::factory()->withPersonalTeam()->create()
            : User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('/user/password', [
            'current_password' => 'wrong-password',
            'password' => 'new-password',
            'password_confirmation' => 'new-password',
        ]);

        $response->assertSessionHasErrors();

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }

    public function test_new_passwords_must_match()
    {
        $user = Features::hasTeamFeatures()
            ? User::factory()->withPersonalTeam()->create()
            : User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('/user/password', [
            'current_password' => 'password',
            'password' => 'new-password',
            'password_confirmation' => 'wrong-password',
        ]);

        $response->assertSessionHasErrors();

        $this->assertTrue(Hash::check('password', $user->fresh()->password));
    }
}

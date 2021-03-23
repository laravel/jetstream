<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Tests\TestCase;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_information_can_be_updated()
    {
        $user = Features::hasTeamFeatures()
                ? User::factory()->withPersonalTeam()->create()
                : User::factory()->create();
        $this->actingAs($user);

        $response = $this->put('/user/profile-information', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
        ]);

        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
    }
}

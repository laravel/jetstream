<?php

namespace Tests\Feature;

use Mockery;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Mockery\MockInterface;

class ProfileInformationTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_information_api_validates_input()
    {
        $this->actingAs(User::factory()->create());

        $this->put('/user/profile-information')
            ->assertSessionHasErrors(['name', 'email'], null, 'updateProfileInformation');
    }

    public function test_email_verification_notification_if_user_implements_must_verify_email()
    {
        $user = Mockery::mock(
            User::factory()->create(),
            MustVerifyEmail::class,
            function (MockInterface $mock) {
                $mock->shouldReceive('sendEmailVerificationNotification')->once();
            }
        );

        $this->actingAs($user);

        $this->put('/user/profile-information', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
        ]);

        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
        $this->assertNull($user->fresh()->email_verified_at);
    }

    public function test_profile_information_can_be_updated()
    {
        $this->actingAs($user = User::factory()->create());

        $this->put('/user/profile-information', [
            'name' => 'Test Name',
            'email' => 'test@example.com',
        ]);

        $this->assertEquals('Test Name', $user->fresh()->name);
        $this->assertEquals('test@example.com', $user->fresh()->email);
    }
}

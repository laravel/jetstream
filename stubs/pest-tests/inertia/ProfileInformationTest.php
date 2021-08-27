<?php

use App\Models\User;

test('profile_information_can_be_updated', function () {
    $this->actingAs($user = User::factory()->create());

    $response = $this->put('/user/profile-information', [
        'name' => 'Test Name',
        'email' => 'test@example.com',
    ]);

    $this->assertEquals('Test Name', $user->fresh()->name);
    $this->assertEquals('test@example.com', $user->fresh()->email);
});

<?php

use App\Models\User;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Support\Facades\Notification;
use Laravel\Fortify\Features;

test('reset_password_link_screen_can_be_rendered', function () {
    if (! Features::enabled(Features::updatePasswords())) {
        return $this->markTestSkipped('Password updates are not enabled.');
    }

    $response = $this->get('/forgot-password');

    $response->assertStatus(200);
});

test('reset_password_link_can_be_requested', function () {
    if (! Features::enabled(Features::updatePasswords())) {
        return $this->markTestSkipped('Password updates are not enabled.');
    }

    Notification::fake();

    $user = User::factory()->create();

    $response = $this->post('/forgot-password', [
        'email' => $user->email,
    ]);

    Notification::assertSentTo($user, ResetPassword::class);
});

test('reset_password_screen_can_be_rendered', function () {
    if (! Features::enabled(Features::updatePasswords())) {
        return $this->markTestSkipped('Password updates are not enabled.');
    }

    Notification::fake();

    $user = User::factory()->create();

    $response = $this->post('/forgot-password', [
        'email' => $user->email,
    ]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) {
        $response = $this->get('/reset-password/'.$notification->token);

        $response->assertStatus(200);

        return true;
    });
});

test('password_can_be_reset_with_valid_token', function () {
    if (! Features::enabled(Features::updatePasswords())) {
        return $this->markTestSkipped('Password updates are not enabled.');
    }

    Notification::fake();

    $user = User::factory()->create();

    $response = $this->post('/forgot-password', [
        'email' => $user->email,
    ]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($user) {
        $response = $this->post('/reset-password', [
            'token' => $notification->token,
            'email' => $user->email,
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertSessionHasNoErrors();

        return true;
    });
});

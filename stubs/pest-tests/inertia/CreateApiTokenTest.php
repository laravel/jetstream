<?php

use App\Models\User;
use Illuminate\Support\Carbon;
use Laravel\Jetstream\Features;

test('api tokens can be created', function () {
    if (Features::hasTeamFeatures()) {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    } else {
        $this->actingAs($user = User::factory()->create());
    }

    $response = $this->post('/user/api-tokens', [
        'name' => 'Test Token',
        'expires_at' => null,
        'permissions' => [
            'read',
            'update',
        ],
    ]);

    expect($user->fresh()->tokens)->toHaveCount(1);
    expect($user->fresh()->tokens->first())
        ->name->toEqual('Test Token')
        ->expires_at->toBeNull()
        ->can('read')->toBeTrue()
        ->can('delete')->toBeFalse();
})->skip(function () {
    return ! Features::hasApiFeatures();
}, 'API support is not enabled.');

test('api tokens can be created with expires at date', function () {
    if (Features::hasTeamFeatures()) {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    } else {
        $this->actingAs($user = User::factory()->create());
    }

    $response = $this->post('/user/api-tokens', [
        'name' => 'Test Token With Expires At',
        'expires_at' => now()->addDay()->format('Y-m-d'),
        'permissions' => [
            'read',
            'update',
        ],
    ]);

    expect($user->fresh()->tokens)->toHaveCount(1);
    expect($user->fresh()->tokens->first())
        ->name->toEqual('Test Token With Expires At')
        ->expires_at->toEqual(Carbon::parse(now()->addDay()->format('Y-m-d')))
        ->can('read')->toBeTrue()
        ->can('delete')->toBeFalse();
})->skip(function () {
    return ! Features::hasApiFeatures();
}, 'API support is not enabled.');

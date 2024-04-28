<?php

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

test('api tokens can be deleted', function () {
    if (Features::hasTeamFeatures()) {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    } else {
        $this->actingAs($user = User::factory()->create());
    }

    $token = $user->tokens()->create([
        'name' => 'Test Token',
        'token' => Str::random(40),
        'abilities' => ['create', 'read'],
    ]);

    $this->delete('/user/api-tokens/'.$token->id);

    expect($user->fresh()->tokens)->toHaveCount(0);
})->skip(function () {
    return ! Features::hasApiFeatures();
}, 'API support is not enabled.');

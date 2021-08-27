<?php

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

test('api token permissions can be updated', function () {
    if (! Features::hasApiFeatures()) {
        return $this->markTestSkipped('API support is not enabled.');
    }

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

    $response = $this->put('/user/api-tokens/'.$token->id, [
        'name' => $token->name,
        'permissions' => [
            'delete',
            'missing-permission',
        ],
    ]);

    $this->assertTrue($user->fresh()->tokens->first()->can('delete'));
    $this->assertFalse($user->fresh()->tokens->first()->can('read'));
    $this->assertFalse($user->fresh()->tokens->first()->can('missing-permission'));
});

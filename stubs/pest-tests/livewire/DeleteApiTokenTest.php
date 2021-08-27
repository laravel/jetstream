<?php

use App\Models\User;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;

test('api tokens can be deleted', function ()
{
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

    Livewire::test(ApiTokenManager::class)
                ->set(['apiTokenIdBeingDeleted' => $token->id])
                ->call('deleteApiToken');

    $this->assertCount(0, $user->fresh()->tokens);
});

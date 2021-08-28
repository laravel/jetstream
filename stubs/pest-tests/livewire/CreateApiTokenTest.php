<?php

use App\Models\User;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;

test('api tokens can be created', function () {
    if (! Features::hasApiFeatures()) {
        return $this->markTestSkipped('API support is not enabled.');
    }

    if (Features::hasTeamFeatures()) {
        $this->actingAs($user = User::factory()->withPersonalTeam()->create());
    } else {
        $this->actingAs($user = User::factory()->create());
    }

    Livewire::test(ApiTokenManager::class)
                ->set(['createApiTokenForm' => [
                    'name' => 'Test Token',
                    'permissions' => [
                        'read',
                        'update',
                    ],
                ]])
                ->call('createApiToken');

    expect($user->fresh()->tokens)->toHaveCount(1);
    expect($user->fresh()->tokens->first()->name)->toEqual('Test Token');
    expect($user->fresh()->tokens->first()->can('read'))->toBeTrue();
    expect($user->fresh()->tokens->first()->can('delete'))->toBeFalse();
});

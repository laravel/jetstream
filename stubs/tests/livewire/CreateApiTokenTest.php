<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\ApiTokenManager;
use Livewire\Livewire;
use Tests\TestCase;

class CreateApiTokenTest extends TestCase
{
    use RefreshDatabase;

    public function test_api_tokens_can_be_created()
    {
        if (! Features::hasApiFeatures()) {
            return $this->markTestSkipped('API support is not enabled.');
        }

        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        Livewire::test(ApiTokenManager::class)
                    ->set(['createApiTokenForm' => [
                        'name' => 'Test Token',
                        'expires_at' => null,
                        'permissions' => [
                            'read',
                            'update',
                        ],
                    ]])
                    ->call('createApiToken');

        $this->assertCount(1, $user->fresh()->tokens);
        $this->assertEquals('Test Token', $user->fresh()->tokens->first()->name);
        $this->assertNull($user->fresh()->tokens->first()->expires_at);
        $this->assertTrue($user->fresh()->tokens->first()->can('read'));
        $this->assertFalse($user->fresh()->tokens->first()->can('delete'));
    }

    public function test_api_tokens_can_be_created_with_expires_at_date()
    {
        if (! Features::hasApiFeatures()) {
            return $this->markTestSkipped('API support is not enabled.');
        }

        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        Livewire::test(ApiTokenManager::class)
                    ->set(['createApiTokenForm' => [
                        'name' => 'Test Token With Expires At',
                        'expires_at' => now()->addDay()->format('Y-m-d'),
                        'permissions' => [
                            'read',
                            'update',
                        ],
                    ]])
                    ->call('createApiToken');

        $this->assertCount(2, $user->fresh()->tokens);
        $this->assertEquals('Test Token With Expires At', $user->fresh()->tokens->latest()->first()->name);
        $this->assertEquals(now()->addDay()->format('Y-m-d'), $user->fresh()->tokens->latest()->first()->expires_at->format('Y-m-d'));
        $this->assertTrue($user->fresh()->tokens->latest()->first()->can('read'));
        $this->assertFalse($user->fresh()->tokens->latest()->first()->can('delete'));
    }
}

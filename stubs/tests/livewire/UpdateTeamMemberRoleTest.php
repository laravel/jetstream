<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\TeamMemberManager;
use Laravel\Jetstream\Features;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateTeamMemberRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_team_member_roles_can_be_updated()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->markTestSkipped('Teams support is not enabled.');
        }

        $this->actingAs($user = User::factory()->withPersonalTeam()->create());

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                        ->set('managingRoleFor', $otherUser)
                        ->set('currentRole', 'editor')
                        ->call('updateRole');

        $this->assertTrue($otherUser->fresh()->hasTeamRole(
            $user->currentTeam->fresh(), 'editor'
        ));
    }

    public function test_only_team_owner_can_update_team_member_roles()
    {
        if (! Features::hasTeamFeatures()) {
            return $this->markTestSkipped('Teams support is not enabled.');
        }

        $user = User::factory()->withPersonalTeam()->create();

        $user->currentTeam->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $this->actingAs($otherUser);

        $component = Livewire::test(TeamMemberManager::class, ['team' => $user->currentTeam])
                        ->set('managingRoleFor', $otherUser)
                        ->set('currentRole', 'editor')
                        ->call('updateRole')
                        ->assertStatus(403);

        $this->assertTrue($otherUser->fresh()->hasTeamRole(
            $user->currentTeam->fresh(), 'admin'
        ));
    }
}

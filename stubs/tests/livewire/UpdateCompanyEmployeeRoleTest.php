<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\CompanyEmployeeManager;
use Livewire\Livewire;
use Tests\TestCase;

class UpdateCompanyEmployeeRoleTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_employee_roles_can_be_updated()
    {
        $this->actingAs($user = User::factory()->withPersonalCompany()->create());

        $user->currentCompany->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                        ->set('managingRoleFor', $otherUser)
                        ->set('currentRole', 'editor')
                        ->call('updateRole');

        $this->assertTrue($otherUser->fresh()->hasCompanyRole(
            $user->currentCompany->fresh(), 'editor'
        ));
    }

    public function test_only_company_owner_can_update_company_employee_roles()
    {
        $user = User::factory()->withPersonalCompany()->create();

        $user->currentCompany->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $this->actingAs($otherUser);

        $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                        ->set('managingRoleFor', $otherUser)
                        ->set('currentRole', 'editor')
                        ->call('updateRole')
                        ->assertStatus(403);

        $this->assertTrue($otherUser->fresh()->hasCompanyRole(
            $user->currentCompany->fresh(), 'admin'
        ));
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\CompanyEmployeeManager;
use Livewire\Livewire;
use Tests\TestCase;

class RemoveCompanyEmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_employees_can_be_removed_from_companies()
    {
        $this->actingAs($user = User::factory()->withPersonalCompany()->create());

        $user->currentCompany->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                        ->set('companyEmployeeIdBeingRemoved', $otherUser->id)
                        ->call('removeCompanyEmployee');

        $this->assertCount(0, $user->currentCompany->fresh()->users);
    }

    public function test_only_company_owner_can_remove_company_employees()
    {
        $user = User::factory()->withPersonalCompany()->create();

        $user->currentCompany->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $this->actingAs($otherUser);

        $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                        ->set('companyEmployeeIdBeingRemoved', $user->id)
                        ->call('removeCompanyEmployee')
                        ->assertStatus(403);
    }
}

<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Http\Livewire\CompanyEmployeeManager;
use Livewire\Livewire;
use Tests\TestCase;

class LeaveCompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_users_can_leave_companies()
    {
        $user = User::factory()->withPersonalCompany()->create();

        $user->currentCompany->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'admin']
        );

        $this->actingAs($otherUser);

        $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                        ->call('leaveCompany');

        $this->assertCount(0, $user->currentCompany->fresh()->users);
    }

    public function test_company_owners_cant_leave_their_own_company()
    {
        $this->actingAs($user = User::factory()->withPersonalCompany()->create());

        $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                        ->call('leaveCompany')
                        ->assertHasErrors(['company']);

        $this->assertNotNull($user->currentCompany->fresh());
    }
}

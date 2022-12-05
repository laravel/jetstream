<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\CompanyEmployeeManager;
use Laravel\Jetstream\Mail\CompanyInvitation;
use Livewire\Livewire;
use Tests\TestCase;

class InviteCompanyEmployeeTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_employees_can_be_invited_to_company()
    {
        if (! Features::sendsCompanyInvitations()) {
            return $this->markTestSkipped('Company invitations not enabled.');
        }

        Mail::fake();

        $this->actingAs($user = User::factory()->withPersonalCompany()->create());

        $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                        ->set('addCompanyEmployeeForm', [
                            'email' => 'test@example.com',
                            'role' => 'admin',
                        ])->call('addCompanyEmployee');

        Mail::assertSent(CompanyInvitation::class);

        $this->assertCount(1, $user->currentCompany->fresh()->companyInvitations);
    }

    public function test_company_employee_invitations_can_be_cancelled()
    {
        if (! Features::sendsCompanyInvitations()) {
            return $this->markTestSkipped('Company invitations not enabled.');
        }

        Mail::fake();

        $this->actingAs($user = User::factory()->withPersonalCompany()->create());

        // Add the company employee...
        $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                        ->set('addCompanyEmployeeForm', [
                            'email' => 'test@example.com',
                            'role' => 'admin',
                        ])->call('addCompanyEmployee');

        $invitationId = $user->currentCompany->fresh()->companyInvitations->first()->id;

        // Cancel the company invitation...
        $component->call('cancelCompanyInvitation', $invitationId);

        $this->assertCount(0, $user->currentCompany->fresh()->companyInvitations);
    }
}

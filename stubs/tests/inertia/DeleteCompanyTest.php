<?php

namespace Tests\Feature;

use App\Models\Company;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeleteCompanyTest extends TestCase
{
    use RefreshDatabase;

    public function test_companies_can_be_deleted()
    {
        $this->actingAs($user = User::factory()->withPersonalCompany()->create());

        $user->ownedCompanies()->save($company = Company::factory()->make([
            'personal_company' => false,
        ]));

        $company->users()->attach(
            $otherUser = User::factory()->create(), ['role' => 'test-role']
        );

        $response = $this->delete('/companies/'.$company->id);

        $this->assertNull($company->fresh());
        $this->assertCount(0, $otherUser->fresh()->companies);
    }

    public function test_personal_companies_cant_be_deleted()
    {
        $this->actingAs($user = User::factory()->withPersonalCompany()->create());

        $response = $this->delete('/companies/'.$user->currentCompany->id);

        $this->assertNotNull($user->currentCompany->fresh());
    }
}

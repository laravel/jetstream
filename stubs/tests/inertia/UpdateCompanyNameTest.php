<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class UpdateCompanyNameTest extends TestCase
{
    use RefreshDatabase;

    public function test_company_names_can_be_updated()
    {
        $this->actingAs($user = User::factory()->withPersonalCompany()->create());

        $response = $this->put('/companies/'.$user->currentCompany->id, [
            'name' => 'Test Company',
        ]);

        $this->assertCount(1, $user->fresh()->ownedCompanies);
        $this->assertEquals('Test Company', $user->currentCompany->fresh()->name);
    }
}

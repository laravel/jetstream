<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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

        $response = $this->delete('/companies/'.$user->currentCompany->id.'/employees/'.$otherUser->id);

        $this->assertCount(0, $user->currentCompany->fresh()->users);
    }

    public function test_company_owners_cant_leave_their_own_company()
    {
        $this->actingAs($user = User::factory()->withPersonalCompany()->create());

        $response = $this->delete('/companies/'.$user->currentCompany->id.'/employees/'.$user->id);

        $response->assertSessionHasErrorsIn('removeCompanyEmployee', ['company']);

        $this->assertNotNull($user->currentCompany->fresh());
    }
}

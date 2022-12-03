<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\CreateCompany;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Company;
use Laravel\Jetstream\Tests\Fixtures\CompanyPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;
use Laravel\Sanctum\Sanctum;
use Laravel\Sanctum\TransientToken;
use Laravel\Jetstream\Tests\OrchestraTestCase;

class CompanyBehaviorTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(\App\Models\Company::class, CompanyPolicy::class);
        Jetstream::useUserModel(User::class);
    }

    public function test_company_relationship_methods()
    {
        $this->migrate();

        $action = new CreateCompany;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $company = $action->create($user, ['name' => 'Test Company']);

        $this->assertInstanceOf(Company::class, $company);

        $this->assertTrue($user->belongsToCompany($company));
        $this->assertTrue($user->ownsCompany($company));
        $this->assertCount(1, $user->fresh()->ownedCompanies);
        $this->assertCount(1, $user->fresh()->allCompanies());

        $company->forceFill(['personal_company' => true])->save();

        $this->assertEquals($company->id, $user->fresh()->personalCompany()->id);
        $this->assertEquals($company->id, $user->fresh()->currentCompany->id);
        $this->assertTrue($user->hasCompanyPermission($company, 'foo'));

        // Test with another user that isn't on the company...
        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $this->assertFalse($otherUser->belongsToCompany($company));
        $this->assertFalse($otherUser->ownsCompany($company));
        $this->assertFalse($otherUser->hasCompanyPermission($company, 'foo'));

        // Add the other user to the company...
        Jetstream::role('editor', 'Editor', ['foo']);

        $otherUser->companies()->attach($company, ['role' => 'editor']);
        $otherUser = $otherUser->fresh();

        $this->assertTrue($otherUser->belongsToCompany($company));
        $this->assertFalse($otherUser->ownsCompany($company));

        $this->assertTrue($otherUser->hasCompanyPermission($company, 'foo'));
        $this->assertFalse($otherUser->hasCompanyPermission($company, 'bar'));

        $this->assertTrue($company->userHasPermission($otherUser, 'foo'));
        $this->assertFalse($company->userHasPermission($otherUser, 'bar'));

        $otherUser->withAccessToken(new TransientToken);

        $this->assertTrue($otherUser->belongsToCompany($company));
        $this->assertFalse($otherUser->ownsCompany($company));

        $this->assertTrue($otherUser->hasCompanyPermission($company, 'foo'));
        $this->assertFalse($otherUser->hasCompanyPermission($company, 'bar'));

        $this->assertTrue($company->userHasPermission($otherUser, 'foo'));
        $this->assertFalse($company->userHasPermission($otherUser, 'bar'));
    }

    public function test_has_company_permission_checks_token_permissions()
    {
        Jetstream::role('admin', 'Administrator', ['foo']);

        $this->migrate();

        $action = new CreateCompany;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $company = $action->create($user, ['name' => 'Test Company']);

        $adam = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $authToken = new Sanctum;
        $adam = $authToken->actingAs($adam, ['bar'], []);

        $company->users()->attach($adam, ['role' => 'admin']);

        $this->assertFalse($adam->hasCompanyPermission($company, 'foo'));

        $john = User::forceCreate([
            'name' => 'John Doe',
            'email' => 'john@doe.com',
            'password' => 'secret',
        ]);

        $authToken = new Sanctum;
        $john = $authToken->actingAs($john, ['foo'], []);

        $company->users()->attach($john, ['role' => 'admin']);

        $this->assertTrue($john->hasCompanyPermission($company, 'foo'));
    }

    public function test_user_does_not_need_to_refresh_after_switching_companies()
    {
        $this->migrate();

        $action = new CreateCompany;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $personalCompany = $action->create($user, ['name' => 'Personal Company']);

        $personalCompany->forceFill(['personal_company' => true])->save();

        $this->assertTrue($user->isCurrentCompany($personalCompany));

        $anotherCompany = $action->create($user, ['name' => 'Test Company']);

        $this->assertTrue($user->isCurrentCompany($anotherCompany));
    }

    protected function migrate()
    {
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

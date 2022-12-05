<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\CreateCompany;;
use App\Actions\Jetstream\InviteCompanyEmployee;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\CompanyPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;
use Laravel\Jetstream\Tests\OrchestraTestCase;

class InviteCompanyEmployeeTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Company::class, CompanyPolicy::class);

        Jetstream::useUserModel(User::class);
    }

    public function test_company_employees_can_be_invited()
    {
        Mail::fake();

        Jetstream::role('admin', 'Admin', ['foo']);

        $this->migrate();

        $company = $this->createCompany();

        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $action = new InviteCompanyEmployee;

        $action->invite($company->owner, $company, 'adam@laravel.com', 'admin');

        $company = $company->fresh();

        $this->assertCount(0, $company->users);
        $this->assertCount(1, $company->companyInvitations);
        $this->assertEquals('adam@laravel.com', $company->companyInvitations->first()->email);
        $this->assertEquals($company->id, $company->companyInvitations->first()->company->id);
    }

    public function test_user_cant_already_be_on_company()
    {
        Mail::fake();

        $this->expectException(ValidationException::class);

        $this->migrate();

        $company = $this->createCompany();

        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $action = new InviteCompanyEmployee;

        $action->invite($company->owner, $company, 'adam@laravel.com', 'admin');
        $this->assertTrue(true);
        $action->invite($company->owner, $company->fresh(), 'adam@laravel.com', 'admin');
    }

    protected function createCompany()
    {
        $action = new CreateCompany;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        return $action->create($user, ['name' => 'Test Company']);
    }

    protected function migrate()
    {
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

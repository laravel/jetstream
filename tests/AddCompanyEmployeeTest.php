<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\AddCompanyEmployee;
use App\Actions\Jetstream\CreateCompany;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Employeeship;
use Laravel\Jetstream\Tests\Fixtures\CompanyPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;
use Laravel\Sanctum\TransientToken;
use Laravel\Jetstream\Tests\OrchestraTestCase;

class AddCompanyEmployeeTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Company::class, CompanyPolicy::class);

        Jetstream::useUserModel(User::class);
    }

    public function test_company_employees_can_be_added()
    {
        Jetstream::role('admin', 'Admin', ['foo']);

        $this->migrate();

        $company = $this->createCompany();

        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $action = new AddCompanyEmployee;

        $action->add($company->owner, $company, 'adam@laravel.com', 'admin');

        $company = $company->fresh();

        $this->assertCount(1, $company->users);

        $this->assertInstanceOf(Employeeship::class, $company->users[0]->employeeship);

        $this->assertTrue($otherUser->hasCompanyRole($company, 'admin'));
        $this->assertFalse($otherUser->hasCompanyRole($company, 'editor'));
        $this->assertFalse($otherUser->hasCompanyRole($company, 'foobar'));

        $company->users->first()->withAccessToken(new TransientToken);

        $this->assertTrue($company->users->first()->hasCompanyPermission($company, 'foo'));
        $this->assertFalse($company->users->first()->hasCompanyPermission($company, 'bar'));
    }

    public function test_user_email_address_must_exist()
    {
        $this->expectException(ValidationException::class);

        $this->migrate();

        $company = $this->createCompany();

        $action = new AddCompanyEmployee;

        $action->add($company->owner, $company, 'missing@laravel.com', 'admin');

        $this->assertCount(1, $company->fresh()->users);
    }

    public function test_user_cant_already_be_on_company()
    {
        $this->expectException(ValidationException::class);

        $this->migrate();

        $company = $this->createCompany();

        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $action = new AddCompanyEmployee;

        $action->add($company->owner, $company, 'adam@laravel.com', 'admin');
        $this->assertTrue(true);
        $action->add($company->owner, $company->fresh(), 'adam@laravel.com', 'admin');
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

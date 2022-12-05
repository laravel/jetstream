<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\CreateCompany;;
use App\Actions\Jetstream\UpdateCompanyName;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\CompanyPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;
use Laravel\Jetstream\Tests\OrchestraTestCase;

class UpdateCompanyTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Company::class, CompanyPolicy::class);
        Jetstream::useUserModel(User::class);
    }

    public function test_company_name_can_be_updated()
    {
        $this->migrate();

        $company = $this->createCompany();

        $action = new UpdateCompanyName;

        $action->update($company->owner, $company, ['name' => 'Test Company Updated']);

        $this->assertSame('Test Company Updated', $company->fresh()->name);
    }

    public function test_name_is_required()
    {
        $this->expectException(ValidationException::class);

        $this->migrate();

        $company = $this->createCompany();

        $action = new UpdateCompanyName;

        $action->update($company->owner, $company, ['name' => '']);
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
        // $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\CreateCompany;
use App\Models\Company;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;
use Laravel\Jetstream\Contracts\AddsCompanyEmployees;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\CompanyPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;
use Laravel\Jetstream\Tests\OrchestraTestCase;

class CompanyInvitationControllerTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Company::class, CompanyPolicy::class);
        Jetstream::useUserModel(User::class);
    }

    public function test_company_invitations_can_be_accepted()
    {
        $this->mock(AddsCompanyEmployees::class)->shouldReceive('add')->once();

        Jetstream::role('admin', 'Admin', ['foo', 'bar']);
        Jetstream::role('editor', 'Editor', ['baz', 'qux']);

        $this->migrate();

        $company = $this->createCompany();

        $invitation = $company->companyInvitations()->create(['email' => 'adam@laravel.com', 'role' => 'admin']);

        $url = URL::signedRoute('company-invitations.accept', ['invitation' => $invitation]);

        $response = $this->actingAs($company->owner)->get($url);

        $response->assertRedirect();
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

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('jetstream.stack', 'inertia');
        $app['config']->set('jetstream.features', ['companies']);
    }
}

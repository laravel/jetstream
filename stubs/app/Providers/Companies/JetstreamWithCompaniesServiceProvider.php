<?php

namespace App\Providers;

use App\Actions\Jetstream\AddCompanyEmployee;
use App\Actions\Jetstream\CreateCompany;
use App\Actions\Jetstream\DeleteCompany;
use App\Actions\Jetstream\DeleteUser;
use App\Actions\Jetstream\InviteCompanyEmployee;
use App\Actions\Jetstream\RemoveCompanyEmployee;
use App\Actions\Jetstream\UpdateCompanyName;
use Illuminate\Support\ServiceProvider;
use Laravel\Jetstream\Jetstream;

class JetstreamServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configurePermissions();

        Jetstream::createCompaniesUsing(CreateCompany::class);
        Jetstream::updateCompanyNamesUsing(UpdateCompanyName::class);
        Jetstream::addCompanyEmployeesUsing(AddCompanyEmployee::class);
        Jetstream::inviteCompanyEmployeesUsing(InviteCompanyEmployee::class);
        Jetstream::removeCompanyEmployeesUsing(RemoveCompanyEmployee::class);
        Jetstream::deleteCompaniesUsing(DeleteCompany::class);
        Jetstream::deleteUsersUsing(DeleteUser::class);
    }

    /**
     * Configure the roles and permissions that are available within the application.
     *
     * @return void
     */
    protected function configurePermissions()
    {
        Jetstream::defaultApiTokenPermissions(['read']);

        Jetstream::role('admin', 'Administrator', [
            'create',
            'read',
            'update',
            'delete',
        ])->description('Administrator users can perform any action.');

        Jetstream::role('editor', 'Editor', [
            'read',
            'create',
            'update',
        ])->description('Editor users have the ability to read, create, and update.');
    }
}

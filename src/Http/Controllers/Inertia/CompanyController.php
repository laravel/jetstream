<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Inertia\Inertia;
use Laravel\Jetstream\Actions\ValidateCompanyDeletion;
use Laravel\Jetstream\Contracts\CreatesCompanies;
use Laravel\Jetstream\Contracts\DeletesCompanies;
use Laravel\Jetstream\Contracts\UpdatesCompanyNames;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\RedirectsActions;

class CompanyController extends Controller
{
    use RedirectsActions;

    /**
     * Show the company management screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $companyId
     * @return \Inertia\Response
     */
    public function show(Request $request, $companyId)
    {
        $company = Jetstream::newCompanyModel()->findOrFail($companyId);

        Gate::authorize('view', $company);

        return Jetstream::inertia()->render($request, 'Companies/Show', [
            'company' => $company->load('owner', 'users', 'companyInvitations'),
            'availableRoles' => array_values(Jetstream::$roles),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
            'permissions' => [
                'canAddCompanyEmployees' => Gate::check('addCompanyEmployee', $company),
                'canDeleteCompany' => Gate::check('delete', $company),
                'canRemoveCompanyEmployees' => Gate::check('removeCompanyEmployee', $company),
                'canUpdateCompany' => Gate::check('update', $company),
            ],
        ]);
    }

    /**
     * Show the company creation screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function create(Request $request)
    {
        Gate::authorize('create', Jetstream::newCompanyModel());

        return Jetstream::inertia()->render($request, 'Companies/Create');
    }

    /**
     * Create a new company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $creator = app(CreatesCompanies::class);

        $creator->create($request->user(), $request->all());

        return $this->redirectPath($creator);
    }

    /**
     * Update the given company's name.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $companyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $companyId)
    {
        $company = Jetstream::newCompanyModel()->findOrFail($companyId);

        app(UpdatesCompanyNames::class)->update($request->user(), $company, $request->all());

        return back(303);
    }

    /**
     * Delete the given company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $companyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $companyId)
    {
        $company = Jetstream::newCompanyModel()->findOrFail($companyId);

        app(ValidateCompanyDeletion::class)->validate($request->user(), $company);

        $deleter = app(DeletesCompanies::class);

        $deleter->delete($company);

        return $this->redirectPath($deleter);
    }
}

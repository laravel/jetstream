<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Jetstream\Actions\UpdateCompanyEmployeeRole;
use Laravel\Jetstream\Contracts\AddsCompanyEmployees;
use Laravel\Jetstream\Contracts\InvitesCompanyEmployees;
use Laravel\Jetstream\Contracts\RemovesCompanyEmployees;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Jetstream;

class CompanyEmployeeController extends Controller
{
    /**
     * Add a new company employee to a company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $companyId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, $companyId)
    {
        $company = Jetstream::newCompanyModel()->findOrFail($companyId);

        if (Features::sendsCompanyInvitations()) {
            app(InvitesCompanyEmployees::class)->invite(
                $request->user(),
                $company,
                $request->email ?: '',
                $request->role
            );
        } else {
            app(AddsCompanyEmployees::class)->add(
                $request->user(),
                $company,
                $request->email ?: '',
                $request->role
            );
        }

        return back(303);
    }

    /**
     * Update the given company employee's role.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $companyId
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $companyId, $userId)
    {
        app(UpdateCompanyEmployeeRole::class)->update(
            $request->user(),
            Jetstream::newCompanyModel()->findOrFail($companyId),
            $userId,
            $request->role
        );

        return back(303);
    }

    /**
     * Remove the given user from the given company.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $companyId
     * @param  int  $userId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $companyId, $userId)
    {
        $company = Jetstream::newCompanyModel()->findOrFail($companyId);

        app(RemovesCompanyEmployees::class)->remove(
            $request->user(),
            $company,
            $user = Jetstream::findUserByIdOrFail($userId)
        );

        if ($request->user()->id === $user->id) {
            return redirect(config('fortify.home'));
        }

        return back(303);
    }
}

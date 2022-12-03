<?php

namespace Laravel\Jetstream\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
use Laravel\Jetstream\Contracts\AddsCompanyEmployees;
use Laravel\Jetstream\CompanyInvitation;

class CompanyInvitationController extends Controller
{
    /**
     * Accept a company invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Jetstream\CompanyInvitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Request $request, CompanyInvitation $invitation)
    {
        app(AddsCompanyEmployees::class)->add(
            $invitation->company->owner,
            $invitation->company,
            $invitation->email,
            $invitation->role
        );

        $invitation->delete();

        return redirect(config('fortify.home'))->banner(
            __('Great! You have accepted the invitation to join the :company company.', ['company' => $invitation->company->name]),
        );
    }

    /**
     * Cancel the given company invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Jetstream\CompanyInvitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, CompanyInvitation $invitation)
    {
        if (! Gate::forUser($request->user())->check('removeCompanyEmployee', $invitation->company)) {
            throw new AuthorizationException;
        }

        $invitation->delete();

        return back(303);
    }
}

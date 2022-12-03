<?php

namespace Laravel\Jetstream\Actions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ValidateCompanyDeletion
{
    /**
     * Validate that the company can be deleted by the given user.
     *
     * @param  mixed  $user
     * @param  mixed  $company
     * @return void
     */
    public function validate($user, $company)
    {
        Gate::forUser($user)->authorize('delete', $company);

        if ($company->personal_company) {
            throw ValidationException::withMessages([
                'company' => __('You may not delete your personal company.'),
            ])->errorBag('deleteCompany');
        }
    }
}

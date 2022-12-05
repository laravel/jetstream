<?php

namespace Laravel\Jetstream\Actions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Events\CompanyEmployeeUpdated;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Rules\Role;

class UpdateCompanyEmployeeRole
{
    /**
     * Update the role for the given company employee.
     *
     * @param  mixed  $user
     * @param  mixed  $company
     * @param  int  $companyEmployeeId
     * @param  string  $role
     * @return void
     */
    public function update($user, $company, $companyEmployeeId, string $role)
    {
        Gate::forUser($user)->authorize('updateCompanyEmployee', $company);

        Validator::make([
            'role' => $role,
        ], [
            'role' => ['required', 'string', new Role],
        ])->validate();

        $company->users()->updateExistingPivot($companyEmployeeId, [
            'role' => $role,
        ]);

        CompanyEmployeeUpdated::dispatch($company->fresh(), Jetstream::findUserByIdOrFail($companyEmployeeId));
    }
}

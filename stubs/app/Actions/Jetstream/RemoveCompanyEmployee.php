<?php

namespace App\Actions\Jetstream;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Contracts\RemovesCompanyEmployees;
use Laravel\Jetstream\Events\CompanyEmployeeRemoved;

class RemoveCompanyEmployee implements RemovesCompanyEmployees
{
    /**
     * Remove the company employee from the given company.
     *
     * @param  mixed  $user
     * @param  mixed  $company
     * @param  mixed  $companyEmployee
     * @return void
     */
    public function remove($user, $company, $companyEmployee)
    {
        $this->authorize($user, $company, $companyEmployee);

        $this->ensureUserDoesNotOwnCompany($companyEmployee, $company);

        $company->removeUser($companyEmployee);

        CompanyEmployeeRemoved::dispatch($company, $companyEmployee);
    }

    /**
     * Authorize that the user can remove the company employee.
     *
     * @param  mixed  $user
     * @param  mixed  $company
     * @param  mixed  $companyEmployee
     * @return void
     */
    protected function authorize($user, $company, $companyEmployee)
    {
        if (! Gate::forUser($user)->check('removeCompanyEmployee', $company) &&
            $user->id !== $companyEmployee->id) {
            throw new AuthorizationException;
        }
    }

    /**
     * Ensure that the currently authenticated user does not own the company.
     *
     * @param  mixed  $companyEmployee
     * @param  mixed  $company
     * @return void
     */
    protected function ensureUserDoesNotOwnCompany($companyEmployee, $company)
    {
        if ($companyEmployee->id === $company->owner->id) {
            throw ValidationException::withMessages([
                'company' => [__('You may not leave a company that you created.')],
            ])->errorBag('removeCompanyEmployee');
        }
    }
}

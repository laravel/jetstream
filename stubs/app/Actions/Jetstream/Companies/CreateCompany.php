<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesCompanies;
use Laravel\Jetstream\Events\AddingCompany;
use Laravel\Jetstream\Jetstream;

class CreateCompany implements CreatesCompanies
{
    /**
     * Validate and create a new company for the given user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create($user, array $input)
    {
        Gate::forUser($user)->authorize('create', Jetstream::newCompanyModel());

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createCompany');

        AddingCompany::dispatch($user);

        $user->switchCompany($company = $user->ownedCompanies()->create([
            'name' => $input['name'],
            'personal_company' => false,
        ]));

        return $company;
    }
}

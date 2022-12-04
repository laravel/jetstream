<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\UpdatesCompanyNames;

class UpdateCompanyName implements UpdatesCompanyNames
{
    /**
     * Validate and update the given company's name.
     *
     * @param  mixed  $user
     * @param  mixed  $company
     * @param  array  $input
     * @return void
     */
    public function update($user, $company, array $input)
    {
        Gate::forUser($user)->authorize('update', $company);

        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('updateCompanyName');

        $company->forceFill([
            'name' => $input['name'],
        ])->save();
    }
}

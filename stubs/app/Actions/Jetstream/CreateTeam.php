<?php

namespace App\Actions\Jetstream;

use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\CreatesTeams;

class CreateTeam implements CreatesTeams
{
    /**
     * Validate and create a new team for the given user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create($user, array $input)
    {
        Validator::make($input, [
            'name' => 'required|string|max:255',
        ])->validateWithBag('createTeam');

        return $user->ownedTeams()->create([
            'name' => $input['name'],
            'personal_team' => false,
        ]);
    }
}

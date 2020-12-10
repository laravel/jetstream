<?php

namespace Laravel\Jetstream\Actions;

use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;

class ValidateTeamTransfer
{
    /**
     * Validate that the team can be deleted by the given user.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @return void
     */
    public function validate($user, $team)
    {
        Gate::forUser($user)->authorize('transferTeam', $team);

        if ($team->personal_team) {
            throw ValidationException::withMessages([
                'team' => __('You may not transfer your personal team.'),
            ]);
        }
    }
}

<?php

namespace App\Actions\Jetstream;

use Laravel\Jetstream\Contracts\SwitchesTeams;
use Laravel\Jetstream\Jetstream;

class SwitchTeam implements SwitchesTeams
{
    /**
     * Switch to selected team.
     *
     * @param  mixed  $user
     * @param  int  $teamId
     *
     * @return mixed
     */
    public function switch($user, int $teamId)
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        if (! $user->switchTeam($team)) {
            abort(403);
        }

        return redirect(config('fortify.home'), 303);
    }
}

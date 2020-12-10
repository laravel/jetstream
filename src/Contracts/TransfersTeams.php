<?php

namespace Laravel\Jetstream\Contracts;

interface TransfersTeams
{
    /**
     * Transfer the given app to the given team.
     *
     * @param  \App\Models\User  $user
     * @param  \App\Models\Team  $team
     * @param  \App\Models\User  $teamMember
     * @return void
     */
    public function transfer($user, $team, $teamMember);
}

<?php

namespace Laravel\Jetstream\Contracts;

interface RemovesTeamMembers
{
    /**
     * Remove the team member from the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  mixed  $teamMember
     * @return void
     */
    public function remove($user, $team, $teamMember);
}

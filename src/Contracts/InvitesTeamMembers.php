<?php

namespace Laravel\Jetstream\Contracts;

interface InvitesTeamMembers
{
    /**
     * Invite a new team member to the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  string  $email
     * @return void
     */
    public function invite($user, $team, string $email, string $role = null);
}

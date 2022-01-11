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
     * @param  array<string>|string|null  $role
     * @return void
     */
    public function invite($user, $team, string $email, $role = []);
}

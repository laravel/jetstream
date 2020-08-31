<?php

namespace Laravel\Jetstream\Contracts;

interface AddsTeamMembers
{
    /**
     * Add a new team member to the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  string  $email
     * @return void
     */
    public function add($user, $team, string $email, string $role = null);
}

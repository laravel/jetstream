<?php

namespace Laravel\Jetstream\Contracts;

interface DeletesTeams
{
    /**
     * Delete the given team.
     *
     * @param  mixed  $team
     * @return void
     */
    public function delete($team);
}

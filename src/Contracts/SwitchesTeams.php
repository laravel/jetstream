<?php

namespace Laravel\Jetstream\Contracts;

interface SwitchesTeams
{
    /**
     * @param mixed $user
     * @param int   $teamId
     *
     * @return mixed
     */
    public function switch($user, int $teamId);
}

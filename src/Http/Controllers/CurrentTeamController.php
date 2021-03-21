<?php

namespace Laravel\Jetstream\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Jetstream\Contracts\SwitchesTeams;

class CurrentTeamController extends Controller
{
    /**
     * Switches the authenticated user's current team.
     *
     * @param \Illuminate\Http\Request                   $request
     * @param \Laravel\Jetstream\Contracts\SwitchesTeams $creator
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, SwitchesTeams $creator)
    {
        return $creator->switch($request->user(), $request->team_id);
    }
}

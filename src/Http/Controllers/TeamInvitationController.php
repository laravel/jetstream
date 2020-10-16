<?php

namespace Laravel\Jetstream\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\TeamInvitation;

class TeamInvitationController extends Controller
{
    /**
     * Accept a team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Jetstream\TeamInvitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function accept(Request $request, TeamInvitation $invitation)
    {
        app(AddsTeamMembers::class)->add(
            $invitation->team->owner,
            $invitation->team,
            $invitation->email,
            $invitation->role
        );

        $invitation->delete();

        return redirect(config('fortify.home'));
    }
}

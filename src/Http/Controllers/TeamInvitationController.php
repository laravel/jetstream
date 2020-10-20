<?php

namespace Laravel\Jetstream\Http\Controllers;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Gate;
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

        return redirect(config('fortify.home'))->banner(
            __('Great! You have accepted the invitation to join the :team team.', ['team' => $invitation->team->name]),
        );
    }

    /**
     * Cancel the given team invitation.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Jetstream\TeamInvitation  $invitation
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, TeamInvitation $invitation)
    {
        if (! Gate::forUser($request->user())->check('removeTeamMember', $invitation->team)) {
            throw new AuthorizationException;
        }

        $invitation->delete();

        return back(303);
    }
}

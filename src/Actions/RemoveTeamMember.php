<?php

namespace Laravel\Jetstream\Actions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Events\TeamMemberRemoved;

class RemoveTeamMember
{
    /**
     * Remove the team member from the given team.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  mixed  $teamMember
     * @return void
     */
    public function remove($user, $team, $teamMember)
    {
        $this->authorize($user, $team, $teamMember);

        $this->ensureUserDoesNotOwnTeam($teamMember, $team);

        $team->removeUser($teamMember);

        TeamMemberRemoved::dispatch($team, $teamMember);
    }

    /**
     * Authorize that the user can remove the team member.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  mixed  $teamMember
     * @return void
     */
    protected function authorize($user, $team, $teamMember)
    {
        if (! Gate::forUser($user)->check('removeTeamMember', $team) &&
            $user->id !== $teamMember->id) {
            throw new AuthorizationException;
        }
    }

    /**
     * Ensure that the currently authenticated user does not own the team.
     *
     * @param  mixed  $teamMember
     * @param  mixed  $team
     * @return void
     */
    protected function ensureUserDoesNotOwnTeam($teamMember, $team)
    {
        if ($teamMember->id === $team->owner->id) {
            throw ValidationException::withMessages([
                'team' => [__('You may not leave a team that you created.')],
            ])->errorBag('removeTeamMember');
        }
    }
}

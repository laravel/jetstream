<?php

namespace Laravel\Jetstream\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Events\TeamMemberUpdated;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Rules\Role;

class UpdateTeamMemberRole
{
    /**
     * Update the role for the given team member.
     *
     * @param  mixed  $user
     * @param  mixed  $team
     * @param  int  $teamMemberId
     * @param  array<string>|string|null  $role
     * @return void
     */
    public function update($user, $team, $teamMemberId, $role)
    {
        Gate::forUser($user)->authorize('updateTeamMember', $team);
        $role = Arr::wrap($role);
        Validator::make([
            'role.*' => $role,
        ], [
            'role.*' => ['required', 'string', new Role],
        ])->validate();

        $team->users()->updateExistingPivot($teamMemberId, [
            'role' => $role,
        ]);

        TeamMemberUpdated::dispatch($team->fresh(), Jetstream::findUserByIdOrFail($teamMemberId));
    }
}

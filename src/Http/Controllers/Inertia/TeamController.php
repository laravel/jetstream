<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Gate;
use Inertia\Response;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\RedirectsActions;

class TeamController extends Controller
{
    use RedirectsActions;

    /**
     * Show the team management screen.
     */
    public function show(Request $request, int $teamId): Response
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        Gate::authorize('view', $team);

        return Jetstream::inertia()?->render($request, 'Teams/Show', [
            'team' => $team->load('owner', 'users', 'teamInvitations'),
            'availableRoles' => array_values(Jetstream::$roles),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
            'permissions' => [
                'canAddTeamMembers' => Gate::check('addTeamMember', $team),
                'canDeleteTeam' => Gate::check('delete', $team),
                'canRemoveTeamMembers' => Gate::check('removeTeamMember', $team),
                'canUpdateTeam' => Gate::check('update', $team),
            ],
        ]);
    }

    /**
     * Show the team creation screen.
     */
    public function create(Request $request): Response
    {
        Gate::authorize('create', Jetstream::newTeamModel());

        return Jetstream::inertia()?->render($request, 'Teams/Create');
    }

    /**
     * Create a new team.
     */
    public function store(Request $request): RedirectResponse|Response|Redirector
    {
        $creator = app(CreatesTeams::class);

        $creator->create($request->user(), $request->all());

        return $this->redirectPath($creator);
    }

    /**
     * Update the given team's name.
     */
    public function update(Request $request, int $teamId): RedirectResponse
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        app(UpdatesTeamNames::class)->update($request->user(), $team, $request->all());

        return back(303);
    }

    /**
     * Delete the given team.
     *
     * @throws AuthorizationException
     */
    public function destroy(Request $request, int $teamId): RedirectResponse|Response|Redirector
    {
        $team = Jetstream::newTeamModel()->findOrFail($teamId);

        app(ValidateTeamDeletion::class)->validate($request->user(), $team);

        $deleter = app(DeletesTeams::class);

        $deleter->delete($team);

        return $this->redirectPath($deleter);
    }
}

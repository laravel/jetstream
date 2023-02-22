<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class DeleteTeamForm extends Component
{
    use RedirectsActions;

    /**
     * The team instance.
     */
    public mixed $team;

    /**
     * Indicates if team deletion is being confirmed.
     */
    public bool $confirmingTeamDeletion = false;

    /**
     * Mount the component.
     */
    public function mount(mixed $team): void
    {
        $this->team = $team;
    }

    /**
     * Delete the team.
     *
     * @throws AuthorizationException
     */
    public function deleteTeam(ValidateTeamDeletion $validator, DeletesTeams $deleter): Response|Redirector|RedirectResponse
    {
        $validator->validate(Auth::user(), $this->team);

        $deleter->delete($this->team);

        return $this->redirectPath($deleter);
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('teams.delete-team-form');
    }
}

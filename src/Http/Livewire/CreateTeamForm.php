<?php

namespace Laravel\Jetstream\Http\Livewire;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class CreateTeamForm extends Component
{
    use RedirectsActions;

    /**
     * The component's state.
     */
    public array $state = [];

    /**
     * Create a new team.
     */
    public function createTeam(CreatesTeams $creator): Response|Redirector|RedirectResponse
    {
        $this->resetErrorBag();

        $creator->create(Auth::user(), $this->state);

        return $this->redirectPath($creator);
    }

    /**
     * Get the current user of the application.
     */
    public function getUserProperty(): User|Authenticatable|null
    {
        return Auth::user();
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('teams.create-team-form');
    }
}

<?php

namespace Laravel\Jetstream\Http\Livewire;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;
use Livewire\Component;

class UpdateTeamNameForm extends Component
{
    /**
     * The team instance.
     */
    public mixed $team;

    /**
     * The component's state.
     */
    public array $state = [];

    /**
     * Mount the component.
     */
    public function mount(mixed $team): void
    {
        $this->team = $team;

        $this->state = $team->withoutRelations()->toArray();
    }

    /**
     * Update the team's name.
     */
    public function updateTeamName(UpdatesTeamNames $updater): void
    {
        $this->resetErrorBag();

        $updater->update($this->user, $this->team, $this->state);

        $this->emit('saved');

        $this->emit('refresh-navigation-menu');
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
        return view('teams.update-team-name-form');
    }
}

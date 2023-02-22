<?php

namespace Laravel\Jetstream\Http\Livewire;

use App\Models\User;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Jetstream\Actions\UpdateTeamMemberRole;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Contracts\RemovesTeamMembers;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\RedirectsActions;
use Laravel\Jetstream\Role;
use Livewire\Component;

class TeamMemberManager extends Component
{
    use RedirectsActions;

    /**
     * The team instance.
     */
    public mixed $team;

    /**
     * Indicates if a user's role is currently being managed.
     */
    public bool $currentlyManagingRole = false;

    /**
     * The user that is having their role managed.
     */
    public mixed $managingRoleFor;

    /**
     * The current role for the user that is having their role managed.
     */
    public string $currentRole;

    /**
     * Indicates if the application is confirming if a user wishes to leave the current team.
     */
    public bool $confirmingLeavingTeam = false;

    /**
     * Indicates if the application is confirming if a team member should be removed.
     */
    public bool $confirmingTeamMemberRemoval = false;

    /**
     * The ID of the team member being removed.
     */
    public int|null $teamMemberIdBeingRemoved = null;

    /**
     * The "add team member" form state.
     *
     * @var array<string, mixed>
     */
    public $addTeamMemberForm = [
        'email' => '',
        'role' => null,
    ];

    /**
     * Mount the component.
     */
    public function mount(mixed $team): void
    {
        $this->team = $team;
    }

    /**
     * Add a new team member to a team.
     */
    public function addTeamMember(): void
    {
        $this->resetErrorBag();

        if (Features::sendsTeamInvitations()) {
            app(InvitesTeamMembers::class)->invite(
                $this->user,
                $this->team,
                $this->addTeamMemberForm['email'],
                $this->addTeamMemberForm['role']
            );
        } else {
            app(AddsTeamMembers::class)->add(
                $this->user,
                $this->team,
                $this->addTeamMemberForm['email'],
                $this->addTeamMemberForm['role']
            );
        }

        $this->addTeamMemberForm = [
            'email' => '',
            'role' => null,
        ];

        $this->team = $this->team->fresh();

        $this->emit('saved');
    }

    /**
     * Cancel a pending team member invitation.
     */
    public function cancelTeamInvitation(int $invitationId): void
    {
        if (! empty($invitationId)) {
            $model = Jetstream::teamInvitationModel();

            $model::whereKey($invitationId)->delete();
        }

        $this->team = $this->team->fresh();
    }

    /**
     * Allow the given user's role to be managed.
     */
    public function manageRole(int $userId): void
    {
        $this->currentlyManagingRole = true;
        $this->managingRoleFor = Jetstream::findUserByIdOrFail($userId);
        $this->currentRole = $this->managingRoleFor->teamRole($this->team)->key;
    }

    /**
     * Save the role for the user being managed.
     *
     * @throws AuthorizationException
     */
    public function updateRole(UpdateTeamMemberRole $updater): void
    {
        $updater->update(
            $this->user,
            $this->team,
            $this->managingRoleFor->id,
            $this->currentRole
        );

        $this->team = $this->team->fresh();

        $this->stopManagingRole();
    }

    /**
     * Stop managing the role of a given user.
     */
    public function stopManagingRole(): void
    {
        $this->currentlyManagingRole = false;
    }

    /**
     * Remove the currently authenticated user from the team.
     */
    public function leaveTeam(RemovesTeamMembers $remover): Response|Redirector|RedirectResponse
    {
        $remover->remove(
            $this->user,
            $this->team,
            $this->user
        );

        $this->confirmingLeavingTeam = false;

        $this->team = $this->team->fresh();

        return $this->redirectPath($remover);
    }

    /**
     * Confirm that the given team member should be removed.
     */
    public function confirmTeamMemberRemoval(int $userId): void
    {
        $this->confirmingTeamMemberRemoval = true;

        $this->teamMemberIdBeingRemoved = $userId;
    }

    /**
     * Remove a team member from the team.
     */
    public function removeTeamMember(RemovesTeamMembers $remover): void
    {
        $remover->remove(
            $this->user,
            $this->team,
            $user = Jetstream::findUserByIdOrFail($this->teamMemberIdBeingRemoved)
        );

        $this->confirmingTeamMemberRemoval = false;

        $this->teamMemberIdBeingRemoved = null;

        $this->team = $this->team->fresh();
    }

    /**
     * Get the current user of the application.
     */
    public function getUserProperty(): User|Authenticatable|null
    {
        return Auth::user();
    }

    /**
     * Get the available team member roles.
     */
    public function getRolesProperty(): array
    {
        return collect(Jetstream::$roles)->transform(function ($role) {
            return with($role->jsonSerialize(), function ($data) {
                return (new Role(
                    $data['key'],
                    $data['name'],
                    $data['permissions']
                ))->description($data['description']);
            });
        })->values()->all();
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('teams.team-member-manager');
    }
}

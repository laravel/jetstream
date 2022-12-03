<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Actions\UpdateCompanyEmployeeRole;
use Laravel\Jetstream\Contracts\AddsCompanyEmployees;
use Laravel\Jetstream\Contracts\InvitesCompanyEmployees;
use Laravel\Jetstream\Contracts\RemovesCompanyEmployees;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Role;
use Livewire\Component;

class CompanyEmployeeManager extends Component
{
    /**
     * The company instance.
     *
     * @var mixed
     */
    public $company;

    /**
     * Indicates if a user's role is currently being managed.
     *
     * @var bool
     */
    public $currentlyManagingRole = false;

    /**
     * The user that is having their role managed.
     *
     * @var mixed
     */
    public $managingRoleFor;

    /**
     * The current role for the user that is having their role managed.
     *
     * @var string
     */
    public $currentRole;

    /**
     * Indicates if the application is confirming if a user wishes to leave the current company.
     *
     * @var bool
     */
    public $confirmingLeavingCompany = false;

    /**
     * Indicates if the application is confirming if a company employee should be removed.
     *
     * @var bool
     */
    public $confirmingCompanyEmployeeRemoval = false;

    /**
     * The ID of the company employee being removed.
     *
     * @var int|null
     */
    public $companyEmployeeIdBeingRemoved = null;

    /**
     * The "add company employee" form state.
     *
     * @var array
     */
    public $addCompanyEmployeeForm = [
        'email' => '',
        'role' => null,
    ];

    /**
     * Mount the component.
     *
     * @param  mixed  $company
     * @return void
     */
    public function mount($company)
    {
        $this->company = $company;
    }

    /**
     * Add a new company employee to a company.
     *
     * @return void
     */
    public function addCompanyEmployee()
    {
        $this->resetErrorBag();

        if (Features::sendsCompanyInvitations()) {
            app(InvitesCompanyEmployees::class)->invite(
                $this->user,
                $this->company,
                $this->addCompanyEmployeeForm['email'],
                $this->addCompanyEmployeeForm['role']
            );
        } else {
            app(AddsCompanyEmployees::class)->add(
                $this->user,
                $this->company,
                $this->addCompanyEmployeeForm['email'],
                $this->addCompanyEmployeeForm['role']
            );
        }

        $this->addCompanyEmployeeForm = [
            'email' => '',
            'role' => null,
        ];

        $this->company = $this->company->fresh();

        $this->emit('saved');
    }

    /**
     * Cancel a pending company employee invitation.
     *
     * @param  int  $invitationId
     * @return void
     */
    public function cancelCompanyInvitation($invitationId)
    {
        if (! empty($invitationId)) {
            $model = Jetstream::companyInvitationModel();

            $model::whereKey($invitationId)->delete();
        }

        $this->company = $this->company->fresh();
    }

    /**
     * Allow the given user's role to be managed.
     *
     * @param  int  $userId
     * @return void
     */
    public function manageRole($userId)
    {
        $this->currentlyManagingRole = true;
        $this->managingRoleFor = Jetstream::findUserByIdOrFail($userId);
        $this->currentRole = $this->managingRoleFor->companyRole($this->company)->key;
    }

    /**
     * Save the role for the user being managed.
     *
     * @param  \Laravel\Jetstream\Actions\UpdateCompanyEmployeeRole  $updater
     * @return void
     */
    public function updateRole(UpdateCompanyEmployeeRole $updater)
    {
        $updater->update(
            $this->user,
            $this->company,
            $this->managingRoleFor->id,
            $this->currentRole
        );

        $this->company = $this->company->fresh();

        $this->stopManagingRole();
    }

    /**
     * Stop managing the role of a given user.
     *
     * @return void
     */
    public function stopManagingRole()
    {
        $this->currentlyManagingRole = false;
    }

    /**
     * Remove the currently authenticated user from the company.
     *
     * @param  \Laravel\Jetstream\Contracts\RemovesCompanyEmployees  $remover
     * @return void
     */
    public function leaveCompany(RemovesCompanyEmployees $remover)
    {
        $remover->remove(
            $this->user,
            $this->company,
            $this->user
        );

        $this->confirmingLeavingCompany = false;

        $this->company = $this->company->fresh();

        return redirect(config('fortify.home'));
    }

    /**
     * Confirm that the given company employee should be removed.
     *
     * @param  int  $userId
     * @return void
     */
    public function confirmCompanyEmployeeRemoval($userId)
    {
        $this->confirmingCompanyEmployeeRemoval = true;

        $this->companyEmployeeIdBeingRemoved = $userId;
    }

    /**
     * Remove a company employee from the company.
     *
     * @param  \Laravel\Jetstream\Contracts\RemovesCompanyEmployees  $remover
     * @return void
     */
    public function removeCompanyEmployee(RemovesCompanyEmployees $remover)
    {
        $remover->remove(
            $this->user,
            $this->company,
            $user = Jetstream::findUserByIdOrFail($this->companyEmployeeIdBeingRemoved)
        );

        $this->confirmingCompanyEmployeeRemoval = false;

        $this->companyEmployeeIdBeingRemoved = null;

        $this->company = $this->company->fresh();
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Get the available company employee roles.
     *
     * @return array
     */
    public function getRolesProperty()
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
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('companies.company-employee-manager');
    }
}

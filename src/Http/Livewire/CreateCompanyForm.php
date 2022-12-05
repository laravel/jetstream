<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\CreatesCompanies;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class CreateCompanyForm extends Component
{
    use RedirectsActions;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * Create a new company.
     *
     * @param  \Laravel\Jetstream\Contracts\CreatesCompanies  $creator
     * @return void
     */
    public function createCompany(CreatesCompanies $creator)
    {
        $this->resetErrorBag();

        $creator->create(Auth::user(), $this->state);

        return $this->redirectPath($creator);
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
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('companies.create-company-form');
    }
}

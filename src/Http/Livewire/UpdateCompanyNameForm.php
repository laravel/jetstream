<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\UpdatesCompanyNames;
use Livewire\Component;

class UpdateCompanyNameForm extends Component
{
    /**
     * The company instance.
     *
     * @var mixed
     */
    public $company;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    /**
     * Mount the component.
     *
     * @param  mixed  $company
     * @return void
     */
    public function mount($company)
    {
        $this->company = $company;

        $this->state = $company->withoutRelations()->toArray();
    }

    /**
     * Update the company's name.
     *
     * @param  \Laravel\Jetstream\Contracts\UpdatesCompanyNames  $updater
     * @return void
     */
    public function updateCompanyName(UpdatesCompanyNames $updater)
    {
        $this->resetErrorBag();

        $updater->update($this->user, $this->company, $this->state);

        $this->emit('saved');

        $this->emit('refresh-navigation-menu');
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
        return view('companies.update-company-name-form');
    }
}

<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Actions\ValidateCompanyDeletion;
use Laravel\Jetstream\Contracts\DeletesCompanies;
use Laravel\Jetstream\RedirectsActions;
use Livewire\Component;

class DeleteCompanyForm extends Component
{
    use RedirectsActions;

    /**
     * The company instance.
     *
     * @var mixed
     */
    public $company;

    /**
     * Indicates if company deletion is being confirmed.
     *
     * @var bool
     */
    public $confirmingCompanyDeletion = false;

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
     * Delete the company.
     *
     * @param  \Laravel\Jetstream\Actions\ValidateCompanyDeletion  $validator
     * @param  \Laravel\Jetstream\Contracts\DeletesCompanies  $deleter
     * @return void
     */
    public function deleteCompany(ValidateCompanyDeletion $validator, DeletesCompanies $deleter)
    {
        $validator->validate(Auth::user(), $this->company);

        $deleter->delete($this->company);

        return $this->redirectPath($deleter);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('companies.delete-company-form');
    }
}

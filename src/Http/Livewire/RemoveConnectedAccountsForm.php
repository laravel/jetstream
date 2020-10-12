<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Livewire\Component;

class RemoveConnectedAccountsForm extends Component
{
    /**
     * Indicates whether or not removal of a provider is being confirmed.
     *
     * @var bool
     */
    public $confirmingRemove = false;

    /**
     * The user's current password.
     *
     * @var string
     */
    public $password = '';

    /**
     * @var mixed
     */
    public $selectedAccountId;

    /**
     * Confirm that the user actually wants to remove the selected connected account.
     *
     * @param  mixed  $accountId
     * @return void
     */
    public function confirmRemove($accountId)
    {
        $this->password = '';

        $this->selectedAccountId = $accountId;

        $this->dispatchBrowserEvent('confirming-remove-oauth-provider');

        $this->confirmingRemove = true;
    }

    /**
     * Remove an OAuth Provider.
     *
     * @param  mixed  $accountId
     * @return void
     */
    public function removeConnectedAccount($accountId)
    {
        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);
        }

        DB::table('connected_accounts')
            ->where('user_id', Auth::user()->getKey())
            ->where('id', $accountId)
            ->delete();

        $this->confirmingRemove = false;
    }

    /**
     * Get the users connected accounts.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAccountsProperty()
    {
        return DB::table('connected_accounts')
                ->where('user_id', Auth::user()->getKey())
                ->orderBy('created_at', 'desc')
                ->get();
    }

    /**
     * Render the component.
     *
     * @return Illuminate\View\View
     */
    public function render()
    {
        return view('profile.remove-connected-accounts-form');
    }
}

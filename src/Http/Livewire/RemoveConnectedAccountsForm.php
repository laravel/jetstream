<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Illuminate\View\Component;

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

    public function confirmRemove()
    {
        $this->password = '';

        $this->dispatchBrowserEvent('confirming-remove-oauth-provider');

        $this->confirmingRemove = true;
    }

    /**
     * Remove an OAuth Provider.
     *
     * @param  mixed  $id
     *
     * @return void
     */
    public function removeConnectedAccount($id)
    {
        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);
        }

        DB::table('connected_accounts')
            ->where('user_id', Auth::user()->getKey())
            ->where('id', $id)
            ->delete();

        $this->confirmingRemove = false;
    }

    /**
     * Get the users connected providers.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getProvidersProperty()
    {
        return DB::table('connected_accounts')
                ->where('user_id', Auth::user()->getKey())
                ->orderBy('created_at', 'desc')
                ->get();
    }

    /**
     * Render the component.
     *
     * @return IlluminateViewView
     */
    public function render()
    {
        return view('profile.remove-connected-accounts-form');
    }
}

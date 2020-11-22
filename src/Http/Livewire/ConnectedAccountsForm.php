<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;

class ConnectedAccountsForm extends Component
{
    /**
     * Indicates whether or not removal of a provider is being confirmed.
     *
     * @var bool
     */
    public $confirmingRemove = false;

    /**
     * @var mixed
     */
    public $selectedAccountId;

    /**
     * Return all socialite providers and whether or not
     * the application supports them.
     *
     * @return array
     */
    public function getProvidersProperty()
    {
        return [
            'facebook' => Jetstream::hasSocialiteSupportFor('facebook'),
            'google' => Jetstream::hasSocialiteSupportFor('google'),
            'twitter' => Jetstream::hasSocialiteSupportFor('twitter'),
            'linkedin' => Jetstream::hasSocialiteSupportFor('linkedin'),
            'github' => Jetstream::hasSocialiteSupportFor('github'),
            'gitlab' => Jetstream::hasSocialiteSupportFor('gitlab'),
            'bitbucket' => Jetstream::hasSocialiteSupportFor('bitbucket'),
        ];
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
     * Confirm that the user actually wants to remove the selected connected account.
     *
     * @param  mixed  $accountId
     * @return void
     */
    public function confirmRemove($accountId)
    {
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
        DB::table('connected_accounts')
            ->where('user_id', Auth::user()->getAuthIdentifier())
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
        return Auth::user()->connectedAccounts
            ->map(function ($account) {
                return (object) [
                    'id' => $account->id,
                    'provider_name' => $account->provider_name,
                    'created_at' => (new \DateTime($account->created_at))->format('d/m/Y H:i'),
                ];
            });
    }

    /**
     * Render the component.
     *
     * @return Illuminate\View\View
     */
    public function render()
    {
        return view('profile.connected-accounts-form');
    }
}

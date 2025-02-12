<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\View\View;
use Laravel\Passport\Token;
use Livewire\Attributes\On;
use Livewire\Component;

class OAuthConnectionManager extends Component
{
    /**
     * The user's connections with OAuth apps.
     *
     * @var \Illuminate\Database\Eloquent\Collection<string, array>
     */
    public $connections;

    /**
     * Indicates if the application is confirming if a connection should be deleted.
     */
    public bool $confirmingConnectionDeletion = false;

    /**
     * The ID of the client its connection being deleted.
     */
    public ?string $connectionClientIdBeingDeleted;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->loadConnections();
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('oauth.oauth-connection-manager');
    }

    /**
     * Get the current user of the application.
     *
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Load the user's connections with OAuth apps.
     */
    #[On('connection-deleted')]
    public function loadConnections(): void
    {
        $this->connections = $this->user->tokens()
            ->with('client')
            ->where('revoked', false)
            ->where('expires_at', '>', Date::now())
            ->get()
            ->reject(fn (Token $token) => $token->client->revoked || $token->client->firstParty())
            ->groupBy('client_id')
            ->map(fn ($tokens) => [
                'client' => $tokens->first()->client,
                'scopes' => $tokens->pluck('scopes')->flatten()->unique()->values()->all(),
                'tokens_count' => $tokens->count(),
            ])
            ->values();
    }

    /**
     * Confirm that the given connection should be deleted.
     */
    public function confirmConnectionDeletion(string $clientId): void
    {
        $this->confirmingConnectionDeletion = true;

        $this->connectionClientIdBeingDeleted = $clientId;
    }

    /**
     * Delete the connection with the OAuth app.
     */
    public function deleteConnection(): void
    {
        $this->user->tokens()
            ->where('client_id', $this->connectionClientIdBeingDeleted)
            ->each(function (Token $token) {
                $token->refreshToken()->delete();
                $token->delete();
            });

        $this->dispatch('connection-deleted');

        $this->confirmingConnectionDeletion = false;
        $this->connectionClientIdBeingDeleted = null;
    }
}

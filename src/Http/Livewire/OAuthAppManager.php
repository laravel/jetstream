<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Laravel\Jetstream\Contracts\CreatesOAuthClients;
use Laravel\Jetstream\Contracts\UpdatesOAuthClients;
use Laravel\Passport\Client;
use Laravel\Passport\Token;
use Livewire\Attributes\On;
use Livewire\Component;

class OAuthAppManager extends Component
{
    /**
     * The user's registered OAuth apps.
     *
     * @var \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Client>
     */
    public $apps;

    /**
     * Create OAuth app form state.
     *
     * @var array<string, mixed>
     */
    public array $createOAuthAppForm = [
        'name' => '',
        'redirect_uris' => [],
        'confidential' => false,
    ];

    /**
     * Indicates if the client credentials is being displayed to the user.
     */
    public bool $displayingClientCredentials = false;

    /**
     * The client credentials.
     */
    public array $clientCredentials = [
        'id' => null,
        'secret' => null,
    ];

    /**
     * Indicates if the user is currently managing an OAuth app.
     *
     * @var bool
     */
    public bool $managingOAuthApp = false;

    /**
     * The OAuth app that is currently being managed.
     *
     * @var \Laravel\Passport\Client|null
     */
    public ?Client $oauthAppBeingManaged;

    /**
     * The update OAuth app form state.
     *
     * @var array
     */
    public array $updateOAuthAppForm = [
        'name' => '',
        'redirect_uris' => [],
    ];

    /**
     * Indicates if the application is confirming if a OAuth app should be deleted.
     */
    public bool $confirmingOAuthAppDeletion = false;

    /**
     * The ID of the OAuth app being deleted.
     */
    public string $oauthAppIdBeingDeleted;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->loadApps();
    }

    /**
     * Render the component.
     */
    public function render(): View
    {
        return view('oauth.oauth-app-manager');
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
     * Load the user's OAuth apps.
     */
    #[On(['app-created', 'app-updated', 'app-deleted'])]
    public function loadApps(): void
    {
        $this->apps = $this->user->clients()
            ->where('revoked', false)
            ->get();
    }

    /**
     * Create a new OAuth Client.
     */
    public function createOAuthApp(CreatesOAuthClients $creator): void
    {
        $this->resetErrorBag();

        $this->displayClientCredentials(
            $creator->create($this->user, $this->createOAuthAppForm)
        );

        $this->createOAuthAppForm['name'] = '';
        $this->createOAuthAppForm['redirect_uris'] = [];
        $this->createOAuthAppForm['confidential'] = false;

        $this->dispatch('app-created');
    }

    /**
     * Display the token value to the user.
     */
    protected function displayClientCredentials(Client $client): void
    {
        $this->displayingClientCredentials = true;

        $this->clientCredentials = [
            'id' => $client->id,
            'secret' => $client->plainSecret,
        ];

        $this->dispatch('client-credentials-displayed');
    }

    /**
     * Allow the given OAuth app to be managed.
     */
    public function manageOAuthApp(string $clientId): void
    {
        $this->managingOAuthApp = true;

        $this->oauthAppBeingManaged = $this->apps->find($clientId);

        $this->updateOAuthAppForm['name'] = $this->oauthAppBeingManaged->name;
        $this->updateOAuthAppForm['redirect_uris'] = $this->oauthAppBeingManaged->redirect_uris;
    }

    /**
     * Update the OAuth app.
     */
    public function updateOAuthApp(UpdatesOAuthClients $updater): void
    {
        $updater->update($this->user, $this->oauthAppBeingManaged, $this->updateOAuthAppForm);

        $this->dispatch('app-updated');

        $this->managingOAuthApp = false;
    }

    /**
     * Confirm that the given OAuth app should be deleted.
     */
    public function confirmOAuthAppDeletion(string $clientId): void
    {
        $this->confirmingOAuthAppDeletion = true;

        $this->oauthAppIdBeingDeleted = $clientId;
    }

    /**
     * Delete the OAuth app.
     */
    public function deleteOAuthApp(): void
    {
        $app = $this->apps->find($this->oauthAppIdBeingDeleted);

        $app->tokens()->each(function (Token $token) {
            $token->refreshToken()->delete();
            $token->delete();
        });

        $app->delete();

        $this->dispatch('app-deleted');

        $this->confirmingOAuthAppDeletion = false;
    }
}

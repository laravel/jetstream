<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Laravel\Passport\Passport;
use Laravel\Passport\PersonalAccessTokenResult;
use Livewire\Attributes\On;
use Livewire\Component;

class PassportApiTokenManager extends Component
{
    /**
     * The user's personal access tokens.
     *
     * @var \Illuminate\Database\Eloquent\Collection<int, \Laravel\Passport\Token>
     */
    public $tokens;

    /**
     * Create API token form state.
     *
     * @var array<string, mixed>
     */
    public array $createApiTokenForm = [
        'name' => '',
        'scopes' => [],
    ];

    /**
     * Indicates if the token is being displayed to the user.
     */
    public bool $displayingToken = false;

    /**
     * The token value.
     */
    public ?string $tokenValue;

    /**
     * Indicates if the application is confirming if an API token should be deleted.
     */
    public bool $confirmingApiTokenDeletion = false;

    /**
     * The ID of the API token being deleted.
     */
    public string $apiTokenIdBeingDeleted;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->createApiTokenForm['scopes'] = Passport::defaultScopes();

        $this->loadTokens();
    }

    /**
     * Load the user's tokens.
     */
    #[On(['token-created', 'token-deleted'])]
    public function loadTokens(): void
    {
        $this->tokens = $this->user->tokens()
            ->with('client')
            ->where('revoked', false)
            ->where('expires_at', '>', Date::now())
            ->get()
            ->filter(fn ($token) => $token->client->hasGrantType('personal_access'));
    }

    /**
     * Create a new API token.
     */
    public function createApiToken(): void
    {
        $this->resetErrorBag();

        Validator::make([
            'name' => $this->createApiTokenForm['name'],
        ], [
            'name' => ['required', 'string', 'max:255'],
        ])->validateWithBag('createApiToken');

        $this->displayTokenValue($this->user->createToken(
            $this->createApiTokenForm['name'],
            Passport::validScopes($this->createApiTokenForm['scopes'])
        ));

        $this->createApiTokenForm['name'] = '';
        $this->createApiTokenForm['scopes'] = Passport::defaultScopes();

        $this->dispatch('token-created');
    }

    /**
     * Display the token value to the user.
     */
    protected function displayTokenValue(PersonalAccessTokenResult $result): void
    {
        $this->displayingToken = true;

        $this->tokenValue = $result->accessToken;

        $this->dispatch('token-displayed');
    }

    /**
     * Confirm that the given API token should be deleted.
     */
    public function confirmApiTokenDeletion(string $tokenId): void
    {
        $this->confirmingApiTokenDeletion = true;

        $this->apiTokenIdBeingDeleted = $tokenId;
    }

    /**
     * Delete the API token.
     */
    public function deleteApiToken(): void
    {
        $this->tokens->find($this->apiTokenIdBeingDeleted)->delete();

        $this->dispatch('token-deleted');

        $this->confirmingApiTokenDeletion = false;
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
     * Render the component.
     */
    public function render(): View
    {
        return view('api.api-token-manager');
    }
}

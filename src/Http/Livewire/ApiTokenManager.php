<?php

namespace Laravel\Jetstream\Http\Livewire;

use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Laravel\Jetstream\Jetstream;
use Laravel\Sanctum\NewAccessToken;
use Laravel\Sanctum\PersonalAccessToken;
use Livewire\Component;

class ApiTokenManager extends Component
{
    /**
     * The create API token form state.
     */
    public array $createApiTokenForm = [
        'name' => '',
        'permissions' => [],
    ];

    /**
     * Indicates if the plain text token is being displayed to the user.
     */
    public bool $displayingToken = false;

    /**
     * The plain text token value.
     */
    public string|null $plainTextToken;

    /**
     * Indicates if the user is currently managing an API token's permissions.
     */
    public bool $managingApiTokenPermissions = false;

    /**
     * The token that is currently having its permissions managed.
     */
    public PersonalAccessToken|null $managingPermissionsFor;

    /**
     * The update API token form state.
     */
    public array $updateApiTokenForm = [
        'permissions' => [],
    ];

    /**
     * Indicates if the application is confirming if an API token should be deleted.
     */
    public bool $confirmingApiTokenDeletion = false;

    /**
     * The ID of the API token being deleted.
     */
    public int $apiTokenIdBeingDeleted;

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        $this->createApiTokenForm['permissions'] = Jetstream::$defaultPermissions;
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
            Jetstream::validPermissions($this->createApiTokenForm['permissions'])
        ));

        $this->createApiTokenForm['name'] = '';
        $this->createApiTokenForm['permissions'] = Jetstream::$defaultPermissions;

        $this->emit('created');
    }

    /**
     * Display the token value to the user.
     */
    protected function displayTokenValue(NewAccessToken $token): void
    {
        $this->displayingToken = true;

        $this->plainTextToken = explode('|', $token->plainTextToken, 2)[1];

        $this->dispatchBrowserEvent('showing-token-modal');
    }

    /**
     * Allow the given token's permissions to be managed.
     */
    public function manageApiTokenPermissions(int $tokenId): void
    {
        $this->managingApiTokenPermissions = true;

        $this->managingPermissionsFor = $this->user->tokens()->where(
            'id', $tokenId
        )->firstOrFail();

        $this->updateApiTokenForm['permissions'] = $this->managingPermissionsFor->abilities;
    }

    /**
     * Update the API token's permissions.
     */
    public function updateApiToken(): void
    {
        $this->managingPermissionsFor->forceFill([
            'abilities' => Jetstream::validPermissions($this->updateApiTokenForm['permissions']),
        ])->save();

        $this->managingApiTokenPermissions = false;
    }

    /**
     * Confirm that the given API token should be deleted.
     */
    public function confirmApiTokenDeletion(int $tokenId): void
    {
        $this->confirmingApiTokenDeletion = true;

        $this->apiTokenIdBeingDeleted = $tokenId;
    }

    /**
     * Delete the API token.
     */
    public function deleteApiToken(): void
    {
        $this->user->tokens()->where('id', $this->apiTokenIdBeingDeleted)->first()->delete();

        $this->user->load('tokens');

        $this->confirmingApiTokenDeletion = false;

        $this->managingPermissionsFor = null;
    }

    /**
     * Get the current user of the application.
     */
    public function getUserProperty(): User|Authenticatable|null
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

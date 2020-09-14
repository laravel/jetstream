<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Jetstream;
use Livewire\Component;

class ApiTokenManager extends Component
{
    /**
     * The create API token form state.
     *
     * @var array
     */
    public $createApiTokenForm = [
        'name' => '',
        'permissions' => [],
    ];

    /**
     * Indicates if the plain text token is being displayed to the user.
     *
     * @var bool
     */
    public $displayingToken = false;

    /**
     * The plain text token value.
     *
     * @var string|null
     */
    public $plainTextToken;

    /**
     * Indicates if the user is currently managing an API token's permissions.
     *
     * @var bool
     */
    public $managingApiTokenPermissions = false;

    /**
     * The token that is currently having its permissions managed.
     *
     * @var \Laravel\Sanctum\PersonalAccessToken|null
     */
    public $managingPermissionsFor;

    /**
     * The update API token form state.
     *
     * @var array
     */
    public $updateApiTokenForm = [
        'permissions' => [],
    ];

    /**
     * Indicates if the application is confirming if an API token should be deleted.
     *
     * @var bool
     */
    public $confirmingApiTokenDeletion = false;

    /**
     * The ID of the API token being deleted.
     *
     * @var int
     */
    public $apiTokenIdBeingDeleted;

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount()
    {
        $this->createApiTokenForm['permissions'] = Jetstream::$defaultPermissions;
    }

    /**
     * Create a new API token.
     *
     * @return void
     */
    public function createApiToken()
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
     *
     * @param  \Laravel\Sanctum\NewAccessToken
     * @return void
     */
    protected function displayTokenValue($token)
    {
        $this->displayingToken = true;

        $this->plainTextToken = explode('|', $token->plainTextToken, 2)[1];
    }

    /**
     * Allow the given token's permissions to be managed.
     *
     * @param  int  $tokenId
     * @return void
     */
    public function manageApiTokenPermissions($tokenId)
    {
        $this->managingApiTokenPermissions = true;

        $this->managingPermissionsFor = $this->user->tokens()->where(
            'id', $tokenId
        )->firstOrFail();

        $this->updateApiTokenForm['permissions'] = $this->managingPermissionsFor->abilities;
    }

    /**
     * Update the API token's permissions.
     *
     * @return void
     */
    public function updateApiToken()
    {
        $this->managingPermissionsFor->forceFill([
            'abilities' => Jetstream::validPermissions($this->updateApiTokenForm['permissions']),
        ])->save();

        $this->managingApiTokenPermissions = false;
    }

    /**
     * Confirm that the given API token should be deleted.
     *
     * @param  int  $tokenId
     * @return void
     */
    public function confirmApiTokenDeletion($tokenId)
    {
        $this->confirmingApiTokenDeletion = true;

        $this->apiTokenIdBeingDeleted = $tokenId;
    }

    /**
     * Delete the API token.
     *
     * @return void
     */
    public function deleteApiToken()
    {
        $this->user->tokens()->where('id', $this->apiTokenIdBeingDeleted)->delete();

        $this->user->load('tokens');

        $this->confirmingApiTokenDeletion = false;

        $this->managingPermissionsFor = null;
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
        return view('api.api-token-manager');
    }
}

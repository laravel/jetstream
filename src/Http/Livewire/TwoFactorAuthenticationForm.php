<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Livewire\Component;

class TwoFactorAuthenticationForm extends Component
{
    /**
     * Indicates if enable is being confirmed.
     *
     * @var bool
     */
    public $confirmingEnableTwoFactorAuthentication = false;

    /**
     * Indicates if disable is being confirmed.
     *
     * @var bool
     */
    public $confirmingDisableTwoFactorAuthentication = false;

    /**
     * Indicates if regenerate is being confirmed.
     *
     * @var bool
     */
    public $confirmingRegenerateRecoveryCodes = false;

    /**
     * The user's current password.
     *
     * @var string
     */
    public $password = '';

    /**
     * Indicates if two factor authentication QR code is being displayed.
     *
     * @var bool
     */
    public $showingQrCode = false;

    /**
     * Indicates if two factor authentication recovery codes are being displayed.
     *
     * @var bool
     */
    public $showingRecoveryCodes = false;

    /**
     * Confirm that the user would like to enable two factor authentication.
     *
     * @return void
     */
    public function confirmEnable()
    {
        $this->password = '';

        $this->dispatchBrowserEvent('confirming-enable-two-factor-authentication');

        $this->confirmingEnableTwoFactorAuthentication = true;
    }

    /**
     * Confirm that the user would like to enable two factor authentication.
     *
     * @return void
     */
    public function confirmDisable()
    {
        $this->password = '';

        $this->dispatchBrowserEvent('confirming-regenerate-recovery-codes');

        $this->confirmingDisableTwoFactorAuthentication = true;
    }

    /**
     * Confirm that the user would like to enable two factor authentication.
     *
     * @return void
     */
    public function confirmRegenerate()
    {
        $this->password = '';

        $this->dispatchBrowserEvent('confirming-disable-two-factor-authentication');

        $this->confirmingRegenerateRecoveryCodes = true;
    }

    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Laravel\Fortify\Actions\EnableTwoFactorAuthentication  $enable
     * @return void
     */
    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $enable)
    {
        $this->resetErrorBag();

        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);
        }

        $enable(Auth::user());

        $this->showingQrCode = true;
        $this->showingRecoveryCodes = true;

        $this->confirmingEnableTwoFactorAuthentication = false;
    }

    /**
     * Generate new recovery codes for the user.
     *
     * @param  \Laravel\Fortify\Actions\GenerateNewRecoveryCodes  $generate
     * @return void
     */
    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate)
    {
        $this->resetErrorBag();

        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);
        }

        $generate(Auth::user());

        $this->showingRecoveryCodes = true;

        $this->confirmingRegenerateRecoveryCodes = false;
    }

    /**
     * Disable two factor authentication for the user.
     *
     * @param  \Laravel\Fortify\Actions\DisableTwoFactorAuthentication  $disable
     * @return void
     */
    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $disable)
    {
        $this->resetErrorBag();

        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);
        }

        $disable(Auth::user());

        $this->confirmingDisableTwoFactorAuthentication = false;
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
     * Determine if two factor authentication is enabled.
     *
     * @return bool
     */
    public function getEnabledProperty()
    {
        return ! empty($this->user->two_factor_secret);
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('profile.two-factor-authentication-form');
    }
}

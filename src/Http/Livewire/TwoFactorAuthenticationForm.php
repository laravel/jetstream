<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication;
use Laravel\Fortify\Actions\DisableTwoFactorAuthentication;
use Laravel\Fortify\Actions\EnableTwoFactorAuthentication;
use Laravel\Fortify\Actions\GenerateNewRecoveryCodes;
use Laravel\Fortify\Features;
use Laravel\Jetstream\ConfirmsPasswords;
use Livewire\Component;

class TwoFactorAuthenticationForm extends Component
{
    use ConfirmsPasswords;

    /**
     * Indicates if two factor authentication QR code is being displayed.
     *
     * @var bool
     */
    public $showingQrCode = false;

    /**
     * Indicates if the two factor authentication confirmation input and button are being displayed.
     *
     * @var bool
     */
    public $showingConfirmation = false;

    /**
     * Indicates if two factor authentication recovery codes are being displayed.
     *
     * @var bool
     */
    public $showingRecoveryCodes = false;

    /**
     * The OTP code for confirming two factor authentication.
     *
     * @var string|null
     */
    public $code;

    /**
     * Mount the component.
     *
     * @return void
     */
    public function mount()
    {
        if (Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm') &&
            is_null(Auth::user()->two_factor_confirmed_at)) {
            app(DisableTwoFactorAuthentication::class)(Auth::user());
        }
    }

    /**
     * Enable two factor authentication for the user.
     *
     * @param  \Laravel\Fortify\Actions\EnableTwoFactorAuthentication  $enable
     * @return void
     */
    public function enableTwoFactorAuthentication(EnableTwoFactorAuthentication $enable)
    {
        if (Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')) {
            $this->ensurePasswordIsConfirmed();
        }

        $enable(Auth::user());

        $this->showingQrCode = true;

        if (Features::optionEnabled(Features::twoFactorAuthentication(), 'confirm')) {
            $this->showingConfirmation = true;
        } else {
            $this->showingRecoveryCodes = true;
        }
    }

    /**
     * Confirm two factor authentication for the user.
     *
     * @param  \Laravel\Fortify\Actions\ConfirmTwoFactorAuthentication  $confirm
     * @return void
     */
    public function confirmTwoFactorAuthentication(ConfirmTwoFactorAuthentication $confirm)
    {
        if (Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')) {
            $this->ensurePasswordIsConfirmed();
        }

        $confirm(Auth::user(), $this->code);

        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = true;
    }

    /**
     * Display the user's recovery codes.
     *
     * @return void
     */
    public function showRecoveryCodes()
    {
        if (Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')) {
            $this->ensurePasswordIsConfirmed();
        }

        $this->showingRecoveryCodes = true;
    }

    /**
     * Generate new recovery codes for the user.
     *
     * @param  \Laravel\Fortify\Actions\GenerateNewRecoveryCodes  $generate
     * @return void
     */
    public function regenerateRecoveryCodes(GenerateNewRecoveryCodes $generate)
    {
        if (Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')) {
            $this->ensurePasswordIsConfirmed();
        }

        $generate(Auth::user());

        $this->showingRecoveryCodes = true;
    }

    /**
     * Disable two factor authentication for the user.
     *
     * @param  \Laravel\Fortify\Actions\DisableTwoFactorAuthentication  $disable
     * @return void
     */
    public function disableTwoFactorAuthentication(DisableTwoFactorAuthentication $disable)
    {
        if (Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword')) {
            $this->ensurePasswordIsConfirmed();
        }

        $disable(Auth::user());

        $this->showingQrCode = false;
        $this->showingConfirmation = false;
        $this->showingRecoveryCodes = false;
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

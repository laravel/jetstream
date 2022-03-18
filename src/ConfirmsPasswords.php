<?php

namespace Laravel\Jetstream;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmPassword;

trait ConfirmsPasswords
{
    /**
     * Indicates if the user's password is being confirmed.
     *
     * @var bool
     */
    public $confirmingPassword = false;

    /**
     * The ID of the operation being confirmed.
     *
     * @var string|null
     */
    public $confirmableId = null;

    /**
     * The user's password.
     *
     * @var string
     */
    public $confirmablePassword = '';

    /**
     * Start confirming the user's password.
     *
     * @param  string  $confirmableId
     * @return void
     */
    public function startConfirmingPassword(string $confirmableId)
    {
        $this->resetErrorBag();

        if ($this->passwordIsConfirmed()) {
            return $this->dispatchBrowserEvent('password-confirmed', [
                'id' => $confirmableId,
            ]);
        }

        $this->confirmingPassword = true;
        $this->confirmableId = $confirmableId;
        $this->confirmablePassword = '';

        $this->dispatchBrowserEvent('confirming-password');
    }

    /**
     * Stop confirming the user's password.
     *
     * @return void
     */
    public function stopConfirmingPassword()
    {
        $this->confirmingPassword = false;
        $this->confirmableId = null;
        $this->confirmablePassword = '';
    }

    /**
     * Confirm the user's password.
     *
     * @return void
     */
    public function confirmPassword()
    {
        if (! app(ConfirmPassword::class)(app(StatefulGuard::class), Auth::user(), $this->confirmablePassword)) {
            throw ValidationException::withMessages([
                'confirmable_password' => [__('This password does not match our records.')],
            ]);
        }

        session(['auth.password_confirmed_at' => time()]);

        $this->dispatchBrowserEvent('password-confirmed', [
            'id' => $this->confirmableId,
        ]);

        $this->stopConfirmingPassword();
    }

    /**
     * Ensure that the user's password has been recently confirmed.
     *
     * @param  int|null  $maximumSecondsSinceConfirmation
     * @return void
     */
    protected function ensurePasswordIsConfirmed($maximumSecondsSinceConfirmation = null)
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?: config('auth.password_timeout', 900);

        $this->passwordIsConfirmed($maximumSecondsSinceConfirmation) ? null : abort(403);
    }

    /**
     * Determine if the user's password has been recently confirmed.
     *
     * @param  int|null  $maximumSecondsSinceConfirmation
     * @return bool
     */
    protected function passwordIsConfirmed($maximumSecondsSinceConfirmation = null)
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?: config('auth.password_timeout', 900);

        return (time() - session('auth.password_confirmed_at', 0)) < $maximumSecondsSinceConfirmation;
    }
}

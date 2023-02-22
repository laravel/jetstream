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
     */
    public bool $confirmingPassword = false;

    /**
     * The ID of the operation being confirmed.
     */
    public string|null $confirmableId = null;

    /**
     * The user's password.
     */
    public string $confirmablePassword = '';

    /**
     * Start confirming the user's password.
     */
    public function startConfirmingPassword(string|null $confirmableId): void
    {
        $this->resetErrorBag();

        if ($this->passwordIsConfirmed()) {
            $this->dispatchBrowserEvent('password-confirmed', [
                'id' => $confirmableId,
            ]);

            return;
        }

        $this->confirmingPassword = true;
        $this->confirmableId = $confirmableId;
        $this->confirmablePassword = '';

        $this->dispatchBrowserEvent('confirming-password');
    }

    /**
     * Stop confirming the user's password.
     */
    public function stopConfirmingPassword(): void
    {
        $this->confirmingPassword = false;
        $this->confirmableId = null;
        $this->confirmablePassword = '';
    }

    /**
     * Confirm the user's password.
     */
    public function confirmPassword(): void
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
     */
    protected function ensurePasswordIsConfirmed(int|null $maximumSecondsSinceConfirmation = null): void
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?: $this->getPasswordTimeout();

        if (! $this->passwordIsConfirmed($maximumSecondsSinceConfirmation)) {
            abort(403);
        }
    }

    /**
     * Determine if the user's password has been recently confirmed.
     */
    protected function passwordIsConfirmed(int|null $maximumSecondsSinceConfirmation = null): bool
    {
        $maximumSecondsSinceConfirmation = $maximumSecondsSinceConfirmation ?: $this->getPasswordTimeout();

        $passwordConfirmedAt = session('auth.password_confirmed_at', 0);

        if ($passwordConfirmedAt === 0) {
            return false;
        }

        return (time() - $passwordConfirmedAt) < $maximumSecondsSinceConfirmation;
    }

    /**
     * Get the number of seconds that a password confirmation is valid for.
     */
    protected function getPasswordTimeout(): int
    {
        return config('auth.password_timeout', 900);
    }
}

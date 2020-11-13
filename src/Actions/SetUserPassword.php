<?php

namespace Laravel\Jetstream\Actions;

use App\Actions\Fortify\PasswordValidationRules;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Jetstream\Contracts\SetsUserPasswords;

class SetUserPassword implements SetsUserPasswords
{
    use PasswordValidationRules;

    /**
     * Validate and update the user's password.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function set($user, array $input)
    {
        Validator::make($input, [
            'password' => $this->passwordRules(),
        ])->validateWithBag('setPassword');

        $user->forceFill([
            'password' => Hash::make($input['password']),
        ])->save();
    }
}

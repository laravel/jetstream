<?php

namespace App\Actions\Fortify;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;

class UpdateUserProfileInformation implements UpdatesUserProfileInformation
{
    /**
     * Validate and update the given user's profile information.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return void
     */
    public function update($user, array $input)
    {
        $data = Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'photo' => ['nullable', 'mimes:jpg,jpeg,png', 'max:1024'],
        ])->validateWithBag('updateProfileInformation');

        if ($data->email !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $data['email_verified_at'] = null;
        }

        if ($data->photo) {
            $data = array_merge($data, $user->updateProfilePhoto($data->photo));
        }
        
        $user->forceFill($data)->save();
        
        if ($input['email'] !== $user->email &&
            $user instanceof MustVerifyEmail) {
            $user->sendEmailVerificationNotification();
        }
    }
}

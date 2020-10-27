<?php

namespace App\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesUsers;
use Laravel\Jetstream\Features;

class DeleteUser implements DeletesUsers
{
    /**
     * Delete the given user.
     *
     * @param  mixed  $user
     * @return void
     */
    public function delete($user)
    {
        if (Features::managesProfilePhotos()) {
            $user->deleteProfilePhoto();
        }
        $user->delete();
    }
}

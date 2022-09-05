<?php

namespace App\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesUsers;
use Laravel\Jetstream\Jetstream;

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
        if (Jetstream::managesProfilePhotos()) {
            $user->deleteProfilePhoto();
        }
        
        if (Jetstream::hasApiFeatures()) {
            $user->tokens->each->delete();
        }
        
        $user->delete();
    }
}

<?php

namespace App\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesUsers;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\Contracts\HasApiTokens;

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
    
        $userModelTraits = class_uses($user);
    
        if (in_array(HasProfilePhoto::class, $userModelTraits)) {
            $user->deleteProfilePhoto();
        }
    
        if (in_array(HasApiTokens::class, $userModelTraits)) {
            $user->tokens->each->delete();
        }
    
        $user->delete();
    }
}

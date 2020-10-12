<?php

namespace Laravel\Jetstream;

trait HasSocialProviders
{
    public function allProviders()
    {

    }

    /**
     * Get all of the social providers belonging to the user.
     */
    public function socialProviders()
    {
        return $this->belongsToMany(Jetstream::teamModel(), Jetstream::membershipModel())
            ->withPivot('role')
            ->withTimestamps()
            ->as('membership');
    }
}

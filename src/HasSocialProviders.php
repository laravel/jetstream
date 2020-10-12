<?php

namespace Laravel\Jetstream;

use Illuminate\Support\Str;

trait HasSocialProviders
{
    /**
     * Determine if the user has a specific provider type.
     *
     * @param  string  $providerName
     * @return bool
     */
    public function hasSocialProviderType(string $providerName)
    {
        return $this->socialProviders->contains('provider_name', Str::lower($providerName));
    }

    /**
     * Get all of the social providers belonging to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function socialProviders()
    {
        return $this->hasMany(Jetstream::socialProviderModel(), 'user_id');
    }
}

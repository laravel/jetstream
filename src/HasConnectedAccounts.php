<?php

namespace Laravel\Jetstream;

use Illuminate\Support\Str;

trait HasConnectedAccounts
{
    /**
     * Determine if the user has a specific provider type.
     *
     * @param  string  $accountType
     *
     * @return bool
     */
    public function hasAccountType(string $accountType)
    {
        return $this->connectedAccounts->contains('provider_name', Str::lower($accountType));
    }

    /**
     * Get all of the social providers belonging to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function connectedAccounts()
    {
        return $this->hasMany(Jetstream::connectedAccountModel(), 'user_id');
    }
}

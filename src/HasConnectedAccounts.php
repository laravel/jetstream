<?php

namespace Laravel\Jetstream;

use Illuminate\Support\Str;

trait HasConnectedAccounts
{
    /**
     * Determine if the user has a specific account type.
     *
     * @param  string  $accountType
     * @return bool
     */
    public function hasTokenFor(string $provider)
    {
        return $this->connectedAccounts->contains('provider_name', Str::lower($provider));
    }

    /**
     * Attempt to retrieve the token for a given provider.
     *
     * @param  string  $provider
     * @return mixed
     */
    public function getTokenFor(string $provider, $default = null)
    {
        if ($this->hasTokenFor($provider)) {
            $this->connectedAccounts
                ->where('provider_name', Str::lower($provider))
                ->first()
                ->token;
        }

        return $default;
    }

    /**
     * Attempt to find a connected account that belongs to the user,
     * for the given provider and provider id.
     *
     * @param  string  $provider
     * @param  string  $providerId
     * @return \Laravel\Jetstream\ConnectedAccount
     */
    public function getConnectedAccountFor(string $provider, string $providerId)
    {
        return $this->connectedAccounts
            ->where('provider_name', $provider)
            ->where('provider_id', $providerId)
            ->first();
    }

    /**
     * Get all of the connected accounts belonging to the user.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function connectedAccounts()
    {
        return $this->hasMany(Jetstream::connectedAccountModel(), 'user_id');
    }
}

<?php

namespace Laravel\Jetstream\Contracts;

use Laravel\Socialite\Contracts\User as ProviderUserContract;

interface CreatesUserFromProvider
{
    /**
     * Create a new user from a social provider user.
     *
     * @param  string  $provider
     * @param  \Laravel\Socialite\Contracts\User  $providerUser
     * @return \App\Models\User
     */
    public function create(string $provider, ProviderUserContract $providerUser);
}

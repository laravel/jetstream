<?php

namespace Laravel\Jetstream;

use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Jetstream;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

trait ConnectsToSocialProvider
{
    /**
     * Authenticate the user via Socialite.
     *
     * @param  string  $provider
     * @param  \Laravel\Socialite\Contracts\User  $providerUser
     * @return Pipeline
     */
    protected function connectToProvider(string $provider, SocialiteUserContract $providerUser)
    {
        $user = $this->getUser($providerUser);

        if (! $user->getConnectedAccountFor($provider, $providerUser->getId())) {
            $user->connectedAccounts()->create([
                'provider_name' => strtolower($provider),
                'provider_id' => $providerUser->getId(),
                'token' => $providerUser->token,
                'secret' => $providerUser->tokenSecret ?? null,
                'refresh_token' => $providerUser->refreshToken ?? null,
                'expires_at' => $providerUser->expiresAt ?? null,
            ]);
        }

        return $user;
    }

    /**
     * Find a user from a given Socialite provided user, or create a new
     * one from the details given by the provider.
     *
     * @param  \Laravel\Socialite\Contracts\User  $providerUser
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function getUser(SocialiteUserContract $providerUser)
    {
        if (! $user = Auth::user()) {
            $user = Jetstream::userModel()::where('email', $providerUser->getEmail())->first();
        }

        if (! $user) {
            $user = Jetstream::userModel()::create([
                'name' => $providerUser->getName(),
                'email' => $providerUser->getEmail(),
            ]);
        }

        return $user;
    }
}

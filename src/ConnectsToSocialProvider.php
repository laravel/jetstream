<?php

namespace Laravel\Jetstream;

use App\Models\User;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

trait ConnectsToSocialProvider
{
    /**
     * Authenticate the user via Socialite.
     *
     * @param  string  $provider
     * @param  \Laravel\Socialite\Contracts\User  $providerUser
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function connectToProvider(User $user, string $provider, SocialiteUserContract $providerUser)
    {
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
}

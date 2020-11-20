<?php

namespace Laravel\Jetstream\Http;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Routing\Pipeline;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Actions\AttemptToAuthenticate;
use Laravel\Fortify\Actions\EnsureLoginIsNotThrottled;
use Laravel\Fortify\Actions\PrepareAuthenticatedSession;
use Laravel\Fortify\Actions\RedirectIfTwoFactorAuthenticatable;
use Laravel\Fortify\Fortify;
use Laravel\Jetstream\Jetstream;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;

trait AuthenticatesSocialite
{
    /**
     * Authenticate the user via Socialite.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $provider
     * @param  \Laravel\Socialite\Contracts\User  $providerUser
     * @return Pipeline
     */
    protected function authenticate(Request $request, string $provider, SocialiteUserContract $providerUser)
    {
        // Register the callback to be used for resolving and authenticating the user.
        Fortify::authenticateUsing(function () use ($provider, $providerUser) {
            $user = $this->getUser($providerUser);

            return $this->connectAccount(
                $user, $provider, $providerUser
            );
        });

        return (new Pipeline(app()))->send($request)->through(array_filter([
            config('fortify.limiters.login') ? null : EnsureLoginIsNotThrottled::class,
            RedirectIfTwoFactorAuthenticatable::class,
            AttemptToAuthenticate::class,
            PrepareAuthenticatedSession::class,
        ]));
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
        if (Auth::check()) {
            return Auth::user();
        }

        return Jetstream::userModel()::where('email', $providerUser->getEmail())->first() ?? Jetstream::userModel()::create([
            'name' => $providerUser->getName(),
            'email' => $providerUser->getEmail(),
        ]);
    }

    /**
     * Find or create the connected account in the database and associate it with the user.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @param  string  $provider
     * @param  \Laravel\Socialite\Contracts\User  $providerUser
     * @return \Illuminate\Contracts\Auth\Authenticatable
     */
    protected function connectAccount(Authenticatable $user, string $provider, SocialiteUserContract $providerUser)
    {
        if (! $connectedAccount = $user->getConnectedAccountFor($provider, $providerUser->getId())) {
            // Create the connected account...
            $connectedAccount = $user->connectedAccounts()->create([
                'provider_name' => strtolower($provider),
                'provider_id' => $providerUser->getId(),
                'token' => $providerUser->token,
                'secret' => $providerUser->tokenSecret ?? null,
                'refresh_token' => $providerUser->refreshToken ?? null,
                'expires_at' => $providerUser->expiresAt ?? null,
            ]);
        }

        $connectedAccount->touch();

        return $user;
    }
}

<?php

namespace Laravel\Jetstream\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Jetstream\Http\AuthenticatesSocialite;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    use AuthenticatesSocialite;

    /**
     * Get the redirect for the given Socialite provider.
     *
     * @param  string  $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider(string $provider)
    {
        return Socialite::driver($provider)->redirect();
    }

    /**
     * Attempt to log the user in via the provider user returned from Socialite.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $provider
     * @return \Illuminate\Routing\Pipeline
     */
    public function handleProviderCallback(Request $request, string $provider)
    {
        return $this->authenticate(
            $request, $provider, Socialite::driver($provider)->user()
        )->then(function ($request) {
            return app(LoginResponse::class);
        });
    }
}

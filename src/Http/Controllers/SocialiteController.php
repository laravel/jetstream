<?php

namespace Laravel\Jetstream\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Fortify\Contracts\LoginResponse;
use Laravel\Jetstream\Contracts\SetsUserPasswords;
use Laravel\Jetstream\Http\AuthenticatesSocialite;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    use AuthenticatesSocialite;

    /**
     * Sets the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Jetstream\Contracts\SetsUserPasswords  $updater
     * @return \Illuminate\Http\Response
     */
    public function setPassword(Request $request, SetsUserPasswords $setter)
    {
        $setter->set($request->user(), $request->all());

        return $request->wantsJson()
                    ? new JsonResponse('', 200)
                    : back()->with('status', 'password-set');
    }

    /**
     * Get the redirect for the given Socialite provider.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function redirectToProvider(Request $request, string $provider)
    {
        // Indentifies whether the user is already logged in
        // and choosing to connect with a social account - used to show success banner.
        if (! is_null($request->user())) {
            session(['connectAccount' => true]);
        }

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
        )->then(function ($request) use ($provider) {
            if (session('connectAccount')) {
                session()->forget('connectAccount');

                return redirect(config('fortify.home'))->banner(
                    __('You have successfully connected '.ucfirst($provider).' to your account.')
                );
            }

            return app(LoginResponse::class);
        });
    }
}

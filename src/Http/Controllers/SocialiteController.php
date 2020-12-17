<?php

namespace Laravel\Jetstream\Http\Controllers;

use App\Models\ConnectedAccount;
use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\CreatesUserFromProvider;
use Laravel\Jetstream\Contracts\SetsUserPasswords;
use Laravel\Jetstream\Jetstream;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    /**
     * The guard implementation.
     *
     * @var \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected $guard;

    /**
     * The creates user implementation.
     *
     * @var  \Laravel\Jetstream\Contracts\CreatesUserFromProvider;
     */
    protected $createsUser;

    /**
     * Create a new controller instance.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function __construct(StatefulGuard $guard, CreatesUserFromProvider $createsUser)
    {
        $this->guard = $guard;
        $this->createsUser = $createsUser;
    }

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
        session()->put('origin_url', back()->getTargetUrl());

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
        if ($request->has('error')) {
            return Auth::check()
                ? redirect(config('fortify.home'))->dangerBanner($request->error_description)
                : redirect()->route('register')->withErrors($request->error_description);
        }

        $providerAccount = Socialite::driver($provider)->user();

        $account = ConnectedAccount::firstWhere([
            'provider_id' => $providerAccount->getId(),
            'provider_name' => $provider,
        ]);

        // Authenticated...
        if (! is_null($user = Auth::user())) {
            if ($account && $account->user_id !== $user->id) {
                return redirect()->route('profile.show')->dangerBanner(
                    __('This :Provider sign in account is already associated with another user. Please try a different account.', ['provider' => $provider])
                );
            }

            if (! $account) {
                $this->createProviderForUser($user, $provider, $providerAccount);

                return redirect()->route('profile.show')->banner(
                    __('You have successfully connected :Provider to your account.', ['provider' => $provider])
                );

            }

            return redirect()->route('profile.show')->dangerBanner(
                __('This :Provider sign in account is already associated with your user.', ['provider' => $provider])
            );
        }

        // Registration...
        if (session()->get('origin_url') === route('register')) {

            if ($account) {
                return redirect()->route('register')->withErrors(
                    __('An account with that :Provider sign in already exists, please login.', ['provider' => $provider])
                );
            }

            if (! $providerAccount->getEmail()) {
                return redirect()->route('register')->withErrors(
                    __('No email address is associated with this :Provider account. Please try a different account.', ['provider' => $provider])
                );
            }

            if (Jetstream::newUserModel()->where('email', $providerAccount->getEmail())) {
                return redirect()->route('register')->withErrors(
                    __('An account with that email address already exists. Please login to connect your :Provider account.', ['provider' => $provider])
                );
            }

            $account = $this->createProviderForUser(
                $user = $this->createsUser->create($provider, $providerAccount),
                $provider,
                $providerAccount
            );

            $this->guard->login($account->user);

            return redirect(config('fortify.home'));
        }

        if (! $account) {
            return redirect()->route('login')->withErrors(
                __('An account with this :Provider sign in was not found. Please register or try a different sign in method.', ['provider' => $provider])
            );
        }

        $this->guard->login($account->user);

        return redirect(config('fortify.home'));
    }
    /**
     * Authenticate the user via Socialite.
     *
     * @param  string  $provider
     * @param  \Laravel\Socialite\Contracts\User  $providerAccount
     * @return \Laravel\Socialite\Contracts\User
     */
    protected function createProviderForUser(User $user, string $provider, SocialiteUserContract $providerAccount)
    {
        return $user->connectedAccounts()->create([
            'provider_name' => strtolower($provider),
            'provider_id' => $providerAccount->getId(),
            'token' => $providerAccount->token,
            'secret' => $providerAccount->tokenSecret ?? null,
            'refresh_token' => $providerAccount->refreshToken ?? null,
            'expires_at' => $providerAccount->expiresAt ?? null,
        ]);
    }
}

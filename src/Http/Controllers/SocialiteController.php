<?php

namespace Laravel\Jetstream\Http\Controllers;

use App\Models\ConnectedAccount;
use App\Models\User;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\ConnectsToSocialProvider;
use Laravel\Jetstream\Contracts\CreatesUserFromProvider;
use Laravel\Jetstream\Contracts\SetsUserPasswords;
use Laravel\Socialite\Contracts\User as SocialiteUserContract;
use Laravel\Socialite\Facades\Socialite;

class SocialiteController extends Controller
{
    use ConnectsToSocialProvider;

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
            if (Auth::check()) {
                return redirect(config('fortify.home'))->dangerBanner(
                    $request->error_description
                );
            }

            return redirect(config('register'))
                ->withErrors([$request->error_description]);
        }

        $providerUser = Socialite::driver($provider)->user();

        $existing = ConnectedAccount::firstWhere([
            'provider_id' => $providerUser->getId(),
            'provider_name' => $provider,
        ]);

        if ($existing && Auth::check() && Auth::user()->id != $existing->user_id) {
            return redirect(config('fortify.home'))->dangerBanner(
                __('That account is already associated with a user. Please use a different account.')
            );
        }

        if ($existing) {
            $user = $existing->user;
        } else {
            if (! $user = $this->getUser($providerUser)) {
                $user = $this->createsUser->create($provider, $providerUser);
            }
        }

        $this->connectToProvider($user, $provider, $providerUser);

        if (! Auth::check()) {
            $this->guard->login($user);

            return redirect(config('fortify.home'));
        }

        return redirect(config('fortify.home'))->banner(
            __('You have successfully connected '.ucfirst($provider).' to your account.')
        );
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

        return User::where('email', $providerUser->getEmail())->first();
    }
}

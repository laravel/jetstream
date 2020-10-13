<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Http\AuthenticatesConnectedAccounts;
use Laravel\Socialite\Facades\Socialite;

class ConnectedAccountsController extends Controller
{
    use AuthenticatesConnectedAccounts;

    /**
     * Get the redirect for the given Socialite provider.
     *
     * @param  string  $provider
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function show(string $provider)
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
    public function store(Request $request, string $provider)
    {
        return $this->authenticate(
            $request, $provider, Socialite::driver($provider)->user()
        );
    }

    /**
     * Delete a connected account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $connectedAccountId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $connectedAccountId)
    {
        if (! Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ])->errorBag('logoutOtherBrowserSessions');
        }

        $this->removeConnectedAccount($request, $connectedAccountId);

        return back(303);
    }

    /**
     * Remove a connected account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $id
     * @return void
     */
    public function removeConnectedAccount(Request $request, $id)
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::table('connected_accounts')
            ->where('user_id', $request->user()->getKey())
            ->where('id', $id)
            ->delete();
    }
}

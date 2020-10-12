<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class ConnectedAccountsController extends Controller
{
    /**
     * Logout from other browser sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed                     $providerId
     * @return \Inertia\Response
     */
    public function destroy(Request $request, $providerId)
    {
        if (! Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ])->errorBag('logoutOtherBrowserSessions');
        }

        $this->removeConnectedAccount($request, $providerId);

        return back(303);
    }

    /**
     * Remove an OAuth Provider.
     *
     * @param  Request  $request
     * @param  mixed    $id
     * @return void
     */
    public function removeConnectedAccount(Request $request, $id)
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::table('social_providers')
            ->where('user_id', $request->user()->getKey())
            ->where('id', $id)
            ->delete();
    }
}

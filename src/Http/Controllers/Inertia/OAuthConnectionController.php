<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Passport\Token;

class OAuthConnectionController extends Controller
{
    /**
     * Delete the given OAuth connection.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $clientId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, string $clientId)
    {
        $request->user()->tokens()
            ->where('client_id', $clientId)
            ->each(function (Token $token) {
                $token->refreshToken()->delete();
                $token->delete();
            });

        return back(303);
    }
}

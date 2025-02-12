<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Date;
use Laravel\Jetstream\Jetstream;
use Laravel\Passport\Passport;
use Laravel\Passport\Token;

class PassportApiTokenController extends Controller
{
    /**
     * Show the user API token screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function index(Request $request)
    {
        return Jetstream::inertia()->render($request, 'API/Index', [
            'tokens' => $request->user()->tokens()
                ->with('client')
                ->where('revoked', false)
                ->where('expires_at', '>', Date::now())
                ->get()
                ->filter(fn (Token $token) => $token->client->hasGrantType('personal_access'))
                ->map(fn (Token $token) => $token->toArray() + [
                    'issued_ago' => $token->created_at->diffForHumans(),
                    'expires_in' => $token->expires_at->longAbsoluteDiffForHumans(),
                ]),
            'availableScopes' => Passport::scopes(),
            'defaultScopes' => Passport::defaultScopes(),
        ]);
    }

    /**
     * Create a new API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $result = $request->user()->createToken(
            $request->name,
            Passport::validScopes($request->input('scopes', []))
        );

        return back()->with('flash', [
            'token' => $result->accessToken,
        ]);
    }

    /**
     * Delete the given API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tokenId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, string $tokenId)
    {
        $request->user()->tokens()->find($tokenId)->delete();

        return back(303);
    }
}

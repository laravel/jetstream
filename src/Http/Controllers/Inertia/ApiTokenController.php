<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Jetstream\Contracts\ApiIndexViewResponse;
use Laravel\Jetstream\Jetstream;

class ApiTokenController extends Controller
{
    /**
     * Show the user API token screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Laravel\Jetstream\Contracts\ApiIndexViewResponse
     */
    public function index(Request $request): ApiIndexViewResponse
    {
        return app(ApiIndexViewResponse::class);
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

        $token = $request->user()->createToken(
            $request->name,
            Jetstream::validPermissions($request->input('permissions', []))
        );

        return back()->with('flash', [
            'token' => explode('|', $token->plainTextToken, 2)[1],
        ]);
    }

    /**
     * Update the given API token's permissions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tokenId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $tokenId)
    {
        $request->validate([
            'permissions' => 'array',
            'permissions.*' => 'string',
        ]);

        $token = $request->user()->tokens()->where('id', $tokenId)->firstOrFail();

        $token->forceFill([
            'abilities' => Jetstream::validPermissions($request->input('permissions', [])),
        ])->save();

        return back(303);
    }

    /**
     * Delete the given API token.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $tokenId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $tokenId)
    {
        $request->user()->tokens()->where('id', $tokenId)->first()->delete();

        return back(303);
    }
}

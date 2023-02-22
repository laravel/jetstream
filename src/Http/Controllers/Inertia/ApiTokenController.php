<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

class ApiTokenController extends Controller
{
    /**
     * Show the user API token screen.
     */
    public function index(Request $request): Response
    {
        return Jetstream::inertia()?->render($request, 'API/Index', [
            'tokens' => $request->user()->tokens->map(function ($token) {
                return $token->toArray() + [
                    'last_used_ago' => optional($token->last_used_at)->diffForHumans(),
                ];
            }),
            'availablePermissions' => Jetstream::$permissions,
            'defaultPermissions' => Jetstream::$defaultPermissions,
        ]);
    }

    /**
     * Create a new API token.
     */
    public function store(Request $request): RedirectResponse
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
     */
    public function update(Request $request, string $tokenId): RedirectResponse
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
     */
    public function destroy(Request $request, string $tokenId): RedirectResponse
    {
        $request->user()->tokens()->where('id', $tokenId)->first()->delete();

        return back(303);
    }
}

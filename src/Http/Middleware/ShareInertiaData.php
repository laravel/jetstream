<?php

namespace Laravel\Jetstream\Http\Middleware;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

class ShareInertiaData
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        Inertia::share(array_filter([
            'jetstream' => function () use ($request) {
                return [
                    'canManageTwoFactorAuthentication' => Features::canManageTwoFactorAuthentication(),
                    'flash' => $request->session()->get('flash', []),
                    'hasApiFeatures' => Jetstream::hasApiFeatures(),
                    'hasTeamFeatures' => Jetstream::hasTeamFeatures(),
                    'managesProfilePhotos' => Jetstream::managesProfilePhotos(),
                ];
            },
            'user' => function () use ($request) {
                if (Jetstream::hasTeamFeatures() && $request->user()) {
                    $request->user()->currentTeam;
                }

                return array_merge($request->user()->toArray(), array_filter([
                    'all_teams' => Jetstream::hasTeamFeatures() ? $request->user()->allTeams() : null,
                ]), [
                    'two_factor_enabled' => ! is_null($request->user()->two_factor_secret),
                ]);
            },
            'errorBags' => function () {
                return collect(optional(Session::get('errors'))->getBags() ?: [])->mapWithKeys(function ($bag, $key) {
                    return [$key => $bag->messages()];
                })->all();
            },
            'currentRouteName' => Route::currentRouteName(),
        ]));

        return $next($request);
    }
}

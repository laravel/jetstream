<?php

namespace Laravel\Jetstream\Http\Middleware;

use Illuminate\Support\Facades\Gate;
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
        Inertia::version(function () {
            // When we are running on Laravel Vapor, asset URLs are automatically generated
            // for us whenever an asset gets deployed. Because of this, there is no need
            // to retrieve the whole file for hashing, as we can use the URL instead.
            if (config('app.asset_url')) {
                return md5(config('app.asset_url'));
            }

            // Alternatively, when we are running in a regular (non-serverless) environment
            // we'll attempt to use the Laravel Mix asset manifest to generate our hash.
            if (file_exists($manifest = public_path('js/mix-manifest.json'))) {
                return md5_file($manifest);
            }

            return null;
        });

        Inertia::share(array_filter([
            'jetstream' => function () use ($request) {
                return [
                    'canCreateTeams' => $request->user() &&
                                        Jetstream::hasTeamFeatures() &&
                                        Gate::forUser($request->user())->check('create', Jetstream::newTeamModel()),
                    'canManageTwoFactorAuthentication' => Features::canManageTwoFactorAuthentication(),
                    'flash' => $request->session()->get('flash', []),
                    'hasApiFeatures' => Jetstream::hasApiFeatures(),
                    'hasTeamFeatures' => Jetstream::hasTeamFeatures(),
                    'managesProfilePhotos' => Jetstream::managesProfilePhotos(),
                ];
            },
            'user' => function () use ($request) {
                if (! $request->user()) {
                    return;
                }

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

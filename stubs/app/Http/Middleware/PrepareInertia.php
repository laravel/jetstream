<?php

namespace App\Http\Middleware;

use Inertia\Inertia;

class PrepareInertia
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
            // When we are running on Laravel Vapor, asset URLs are automatically updated
            // when the application is being deployed. Because of this, we do not need
            // to use any files for hashing, as we can simply use this URL instead.
            if (config('app.asset_url')) {
                return md5(config('app.asset_url'));
            }

            // Alternatively, when we are running in a regular (non-serverless) environment
            // we'll attempt to use the Laravel Mix asset manifest to generate our hash.
            if (file_exists($manifest = public_path('mix-manifest.json'))) {
                return md5_file($manifest);
            }
        });

        return $next($request);
    }
}

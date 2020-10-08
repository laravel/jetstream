<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Inertia\Inertia;

class InertiaServiceProvider extends ServiceProvider
{
    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->configureVersioning();
    }

    /**
     * Enables Inertia automatic asset cache busting.
     *
     * @return void
     */
    protected function configureVersioning()
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
    }
}

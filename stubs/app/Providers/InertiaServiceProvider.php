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

            return null;
        });
    }
}

<?php

namespace Laravel\Jetstream\Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Fortify\FortifyServiceProvider;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\JetstreamServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\TestCase;

abstract class OrchestraTestCase extends TestCase
{
    use RefreshDatabase;

    protected function getPackageProviders($app)
    {
        return [
            LivewireServiceProvider::class,
            JetstreamServiceProvider::class,
            FortifyServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testing');
    }

    protected function defineDatabaseMigrations()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        $this->loadMigrationsFrom(__DIR__.'/../vendor/laravel/fortify/database/migrations');
    }

    protected function defineHasTeamEnvironment($app)
    {
        $features = $app->config->get('jetstream.features', []);

        $features[] = Features::teams(['invitations' => true]);

        $app->config->set('jetstream.features', $features);
    }
}

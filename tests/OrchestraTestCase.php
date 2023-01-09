<?php

namespace Laravel\Jetstream\Tests;

use Laravel\Fortify\FortifyServiceProvider;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\JetstreamServiceProvider;
use Mockery;
use Orchestra\Testbench\TestCase;

abstract class OrchestraTestCase extends TestCase
{
    public function setUp(): void
    {
        parent::setUp();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    protected function getPackageProviders($app)
    {
        return [JetstreamServiceProvider::class, FortifyServiceProvider::class];
    }

    protected function defineEnvironment($app)
    {
        $app['config']->set('database.default', 'testbench');

        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
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

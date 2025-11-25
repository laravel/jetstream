<?php

namespace Laravel\Jetstream\Tests;

use Illuminate\Foundation\Testing\LazilyRefreshDatabase;
use Laravel\Fortify\FortifyServiceProvider;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\JetstreamServiceProvider;
use Livewire\LivewireServiceProvider;
use Orchestra\Testbench\Attributes\WithConfig;
use Orchestra\Testbench\Concerns\WithWorkbench;
use Orchestra\Testbench\TestCase;

#[WithConfig('database.default', 'testing')]
abstract class OrchestraTestCase extends TestCase
{
    use LazilyRefreshDatabase, WithWorkbench;

    protected function defineHasTeamEnvironment($app)
    {
        $features = $app->config->get('jetstream.features', []);

        $features[] = Features::teams(['invitations' => true]);

        $app->config->set('jetstream.features', $features);
    }
}

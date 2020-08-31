<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\CreateTeam;
use Laravel\Jetstream\Tests\Fixtures\User;

class CurrentTeamControllerTest extends OrchestraTestCase
{
    public function test_can_switch_to_team_the_user_belongs_to()
    {
        $this->migrate();

        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $team = $action->create($user, ['name' => 'Test Team']);

        $response = $this->actingAs($user)->put('/current-team', ['team_id' => $team->id]);

        $response->assertRedirect('/home');

        $this->assertEquals($team->id, $user->fresh()->currentTeam->id);
        $this->assertTrue($user->isCurrentTeam($team));
    }

    public function test_cant_switch_to_team_the_user_does_not_belong_to()
    {
        $this->migrate();

        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $team = $action->create($user, ['name' => 'Test Team']);

        $otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]);

        $response = $this->actingAs($otherUser)->put('/current-team', ['team_id' => $team->id]);

        $response->assertStatus(403);
    }

    protected function migrate()
    {
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }

    protected function getEnvironmentSetUp($app)
    {
        parent::getEnvironmentSetUp($app);

        $app['config']->set('jetstream.stack', 'livewire');
        $app['config']->set('jetstream.features', ['teams']);
    }
}

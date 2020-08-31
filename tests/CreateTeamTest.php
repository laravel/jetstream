<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\CreateTeam;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Team;
use Laravel\Jetstream\Tests\Fixtures\User;

class CreateTeamTest extends OrchestraTestCase
{
    public function test_team_name_can_be_updated()
    {
        $this->migrate();

        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $team = $action->create($user, ['name' => 'Test Team']);

        $this->assertInstanceOf(Team::class, $team);
    }

    public function test_name_is_required()
    {
        $this->expectException(ValidationException::class);

        $this->migrate();

        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        $action->create($user, ['name' => '']);
    }

    protected function migrate()
    {
        // $this->loadLaravelMigrations(['--database' => 'testbench']);

        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

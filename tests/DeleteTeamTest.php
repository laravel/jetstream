<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\DeleteTeam;
use App\Models\Team;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Actions\ValidateTeamDeletion;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\TeamPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;

class DeleteTeamTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Team::class, TeamPolicy::class);
        Jetstream::useUserModel(User::class);
    }

    public function test_team_can_be_deleted()
    {
        $this->migrate();

        $team = $this->createTeam();

        $action = new DeleteTeam;

        $action->delete($team);

        $this->assertNull($team->fresh());
    }

    public function test_team_deletion_can_be_validated()
    {
        Jetstream::useUserModel(User::class);

        $this->migrate();

        $team = $this->createTeam();

        $action = new ValidateTeamDeletion;

        $action->validate($team->owner, $team);

        $this->assertTrue(true);
    }

    public function test_personal_team_cant_be_deleted()
    {
        $this->expectException(ValidationException::class);

        Jetstream::useUserModel(User::class);

        $this->migrate();

        $team = $this->createTeam();

        $team->forceFill(['personal_team' => true])->save();

        $action = new ValidateTeamDeletion;

        $action->validate($team->owner, $team);
    }

    public function test_non_owner_cant_delete_team()
    {
        $this->expectException(AuthorizationException::class);

        Jetstream::useUserModel(User::class);

        $this->migrate();

        $team = $this->createTeam();

        $action = new ValidateTeamDeletion;

        $action->validate(User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]), $team);
    }

    protected function createTeam()
    {
        $action = new CreateTeam;

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        return $action->create($user, ['name' => 'Test Team']);
    }

    protected function migrate()
    {
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

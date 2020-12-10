<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\CreateTeam;
use App\Actions\Jetstream\TransferTeam;
use App\Models\Team;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Actions\ValidateTeamTransfer;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\TeamPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;

class TransferTeamTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Gate::policy(Team::class, TeamPolicy::class);
        Jetstream::useUserModel(User::class);
    }

    public function test_team_can_be_transferred()
    {
        $this->migrate();

        $team = $this->createTeam();

        $team->users()->attach($otherUser = User::forceCreate([
            'name' => 'Adam Wathan',
            'email' => 'adam@laravel.com',
            'password' => 'secret',
        ]), ['role' => 'admin']);

        $action = new TransferTeam;

        $action->transfer($team->owner, $team, $otherUser);

        $this->assertEquals($otherUser->id, $team->owner->id);
    }

    public function test_team_transfer_can_be_validated()
    {
        $this->migrate();

        $team = $this->createTeam();

        $action = new ValidateTeamTransfer;

        $action->validate($team->owner, $team);

        $this->assertTrue(true);
    }

    public function test_personal_team_cant_be_transferred()
    {
        $this->expectException(ValidationException::class);

        $this->migrate();

        $team = $this->createTeam();

        $team->forceFill(['personal_team' => true])->save();

        $action = new ValidateTeamTransfer;

        $action->validate($team->owner, $team);
    }

    public function test_non_owner_cant_transfer_team()
    {
        $this->expectException(AuthorizationException::class);

        Jetstream::useUserModel(User::class);

        $this->migrate();

        $team = $this->createTeam();

        $action = new ValidateTeamTransfer;

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

        $team = $action->create($user, ['name' => 'Test Team']);

        return $team;
    }

    protected function migrate()
    {
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

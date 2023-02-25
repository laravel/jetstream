<?php

namespace Laravel\Jetstream\Tests\Unit;

use App\Actions\Jetstream\UpdateTeamName;
use App\Models\Team;
use Illuminate\Support\Facades\Gate;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\TeamPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;

beforeEach(function () {
    Gate::policy(Team::class, TeamPolicy::class);
    Jetstream::useUserModel(User::class);

    $this->artisan('migrate', ['--database' => 'testbench'])->run();
});

test('team name can be updated', function (): void {
    $team = createTeam();

    $action = new UpdateTeamName;

    $action->update($team->owner, $team, ['name' => 'Test Team Updated']);

    $this->assertSame('Test Team Updated', $team->fresh()->name);
});

test('name is required', function (): void {
    $this->expectException(ValidationException::class);

    $team = createTeam();

    $action = new UpdateTeamName;

    $action->update($team->owner, $team, ['name' => '']);
});

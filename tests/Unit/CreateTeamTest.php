<?php

namespace Laravel\Jetstream\Tests\Unit;

use App\Actions\Jetstream\CreateTeam;
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
    $action = new CreateTeam;

    $user = User::forceCreate([
        'name' => 'Taylor Otwell',
        'email' => 'taylor@laravel.com',
        'password' => 'secret',
    ]);

    $team = $action->create($user, ['name' => 'Test Team']);

    $this->assertInstanceOf(Team::class, $team);
});

test('name is required', function (): void {
    $this->expectException(ValidationException::class);

    $action = new CreateTeam;

    $user = User::forceCreate([
        'name' => 'Taylor Otwell',
        'email' => 'taylor@laravel.com',
        'password' => 'secret',
    ]);

    $action->create($user, ['name' => '']);
});

<?php

namespace Laravel\Jetstream\Tests\Unit;

use App\Models\Team;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\OwnerRole;
use Laravel\Jetstream\Role;
use Laravel\Jetstream\Tests\Fixtures\User as UserFixture;

uses(RefreshDatabase::class);

beforeEach(function () {
    Jetstream::$permissions = [];
    Jetstream::$roles = [];

    Jetstream::useUserModel(UserFixture::class);
});

test('team Role returns an Owner Role for the team owner', function (): void {
    $team = Team::factory()->create();

    expect($team->owner->teamRole($team))->toBeInstanceOf(OwnerRole::class);
});

test('team Role returns the matching role', function (): void {
    Jetstream::role('admin', 'Admin', [
        'read',
        'create',
    ])->description('Admin Description');

    $team = Team::factory()
        ->hasAttached(User::factory(), [
            'role' => 'admin',
        ])
        ->create();
    $role = $team->users->first()->teamRole($team);

    expect($role)->toBeInstanceOf(Role::class)
        ->and($role->key)->toEqual('admin');
});

test('team Role returns null if the user does not belong to the team', function (): void {
    $team = Team::factory()->create();

    expect((new UserFixture())->teamRole($team))->toBeNull();
});

test('team Role returns null if the user does not have a role on the site', function (): void {
    $team = Team::factory()
        ->has(User::factory())
        ->create();

    expect($team->users->first()->teamRole($team))->toBeNull();
});

test('team Permissions returns all for team owners', function (): void {
    $team = Team::factory()->create();

    expect($team->owner->teamPermissions($team))->toEqual(['*']);
});

test('team Permissions returns empty for non members', function (): void {
    $team = Team::factory()->create();

    expect((new UserFixture())->teamPermissions($team))->toEqual([]);
});

test('team Permissions returns permissions for the users role', function (): void {
    Jetstream::role('admin', 'Admin', [
        'read',
        'create',
    ])->description('Admin Description');

    $team = Team::factory()
        ->hasAttached(User::factory(), [
            'role' => 'admin',
        ])
        ->create();

    expect($team->users->first()->teamPermissions($team))->toEqual(['read', 'create']);
});

test('team Permissions returns empty permissions for members without a defined role', function (): void {
    Jetstream::role('admin', 'Admin', [
        'read',
        'create',
    ])->description('Admin Description');

    $team = Team::factory()
        ->has(User::factory())
        ->create();

    expect($team->users->first()->teamPermissions($team))->toEqual([]);
});

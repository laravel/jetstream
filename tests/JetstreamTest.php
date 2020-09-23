<?php

namespace Laravel\Jetstream\Tests;

use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\Permission;
use Laravel\Jetstream\Tests\Fixtures\TeamRole;

class JetstreamTest extends OrchestraTestCase
{
    public function test_roles_can_be_registered()
    {
        Jetstream::$permissions = [];
        Jetstream::$roles = [];

        Jetstream::role('admin', 'Admin', [
            'read',
            'create',
        ])->description('Admin Description');

        Jetstream::role('editor', 'Editor', [
            'read',
            'update',
            'delete',
        ])->description('Editor Description');

        $this->assertTrue(Jetstream::hasPermissions());

        $this->assertEquals([
            'create',
            'delete',
            'read',
            'update',
        ], Jetstream::$permissions);
    }

    public function test_permissions_model_can_be_provided_and_return_permissions()
    {
        $this->migrate();

        Permission::create(['name' => 'create']);
        Permission::create(['name' => 'delete']);
        Permission::create(['name' => 'read']);
        Permission::create(['name' => 'update']);

        Jetstream::$permissions = [];

        $this->assertFalse(Jetstream::hasPermissions());

        Jetstream::usePermissionModel(Permission::class);

        $this->assertInstanceOf(Permission::class, Jetstream::newPermissionModel());
    }

    public function test_roles_model_can_be_provided_and_return_permissions()
    {
        $this->migrate();

        $createPermission = Permission::create(['name' => 'create']);
        $deletePermission = Permission::create(['name' => 'delete']);
        $readPermission = Permission::create(['name' => 'read']);
        $updatePermission = Permission::create(['name' => 'update']);

        TeamRole::create([
            'team_id' => 1,
            'key' => 'admin',
            'label' => 'Admin',
            'description' => 'Admin Description',
        ])->permissions()->saveMany([$readPermission, $createPermission]);

        TeamRole::create([
            'team_id' => 1,
            'key' => 'editor',
            'label' => 'Editor',
            'description' => 'Editor Description',
        ])->permissions()->saveMany([$deletePermission, $readPermission, $updatePermission]);

        Jetstream::$roles = [];

        $this->assertFalse(Jetstream::hasRoles());

        Jetstream::useTeamRoleModel(TeamRole::class);

        $this->assertInstanceOf(TeamRole::class, Jetstream::newTeamRoleModel());
    }

    protected function migrate()
    {
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

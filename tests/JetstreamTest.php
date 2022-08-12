<?php

namespace Laravel\Jetstream\Tests;

use Laravel\Jetstream\Features;
use Laravel\Jetstream\Jetstream;

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

    public function test_roles_can_be_json_serialized()
    {
        Jetstream::$permissions = [];
        Jetstream::$roles = [];

        $role = Jetstream::role('admin', 'Admin', [
            'read',
            'create',
        ])->description('Admin Description');

        $serialized = $role->jsonSerialize();

        $this->assertArrayHasKey('key', $serialized);
        $this->assertArrayHasKey('name', $serialized);
        $this->assertArrayHasKey('description', $serialized);
        $this->assertArrayHasKey('permissions', $serialized);
    }

    /**
     * @define-env defineHasTeamEnvironment
     */
    public function test_user_has_team_feature_can_be_determined_from_the_user()
    {
        $this->assertTrue(Jetstream::userHasTeamFeatures(new Fixtures\User));
        $this->assertFalse(Jetstream::userHasTeamFeatures(new Fixtures\Admin));
    }
}

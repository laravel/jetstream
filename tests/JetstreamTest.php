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

    public function test_has_team_feature_will_always_return_false_when_team_is_not_enabled()
    {
        $this->assertFalse(Jetstream::hasTeamFeatures());
        $this->assertFalse(Jetstream::userHasTeamFeatures(new Fixtures\User));
        $this->assertFalse(Jetstream::userHasTeamFeatures(new Fixtures\Admin));
    }

    /**
     * @define-env defineHasTeamEnvironment
     */
    public function test_has_team_feature_can_be_determined_when_team_is_enabled()
    {
        $this->assertTrue(Jetstream::hasTeamFeatures());
        $this->assertTrue(Jetstream::userHasTeamFeatures(new Fixtures\User));
        $this->assertFalse(Jetstream::userHasTeamFeatures(new Fixtures\Admin));
    }
}

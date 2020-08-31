<?php

namespace Laravel\Jetstream\Tests;

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
}

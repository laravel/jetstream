<?php

namespace Laravel\Jetstream\Tests;

use App\Actions\Jetstream\CreateTeam;
use Illuminate\Contracts\Support\Responsable;
use Illuminate\Http\JsonResponse;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\User;

class JetstreamServiceProviderTest extends OrchestraTestCase
{
    protected function defineEnvironment($app)
    {
        parent::defineEnvironment($app);

        $app['config']->set('jetstream.features', ['api', 'teams']);
    }

    public function test_api_views_can_be_customized()
    {
        Jetstream::apiIndexView(function () {
            return 'foo';
        });

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
            'email_verified_at' => now()
        ]);

        $response = $this->actingAs($user)->get('/user/api-tokens');

        $response->assertOk();

        $this->assertSame('foo', $response->content());
    }

    public function test_customized_api_views_can_return_their_own_responsable()
    {
        Jetstream::apiIndexView(function () {
            return new class implements Responsable
            {
                public function toResponse($request)
                {
                    return new JsonResponse(['foo' => 'bar']);
                }
            };
        });

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
            'email_verified_at' => now()
        ]);

        $response = $this->actingAs($user)->get('/user/api-tokens');

        $response->assertOk();

        $response->assertExactJson(['foo' => 'bar']);
    }

    public function test_profile_views_can_be_customized()
    {
        Jetstream::profileShowView(function () {
            return 'foo';
        });

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
            'email_verified_at' => now()
        ]);

        $response = $this->actingAs($user)->get('/user/profile');

        $response->assertOk();

        $this->assertSame('foo', $response->content());
    }

    public function test_customized_profile_views_can_return_their_own_responsable()
    {
        Jetstream::profileShowView(function () {
            return new class implements Responsable
            {
                public function toResponse($request)
                {
                    return new JsonResponse(['foo' => 'bar']);
                }
            };
        });

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
            'email_verified_at' => now()
        ]);

        $response = $this->actingAs($user)->get('/user/profile');

        $response->assertOk();

        $response->assertExactJson(['foo' => 'bar']);
    }

    public function test_teams_views_can_be_customized()
    {
        Jetstream::teamsCreateView(function () {
            return 'foo';
        });

        Jetstream::teamsShowView(function () {
            return 'foo';
        });

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
            'email_verified_at' => now()
        ]);

        $response = $this->actingAs($user)->get('/teams/create');

        $response->assertOk();

        $this->assertSame('foo', $response->content());

        $team = ( new CreateTeam() )->create($user, ['name' => 'Test Team']);

        $response = $this->actingAs($user)->get('/teams/'.$team->id);

        $response->assertOk();

        $this->assertSame('foo', $response->content());
    }

    public function test_customized_teams_views_can_return_their_own_responsable()
    {
        Jetstream::teamsCreateView(function () {
            return new class implements Responsable
            {
                public function toResponse($request)
                {
                    return new JsonResponse(['foo' => 'bar']);
                }
            };
        });

        Jetstream::teamsShowView(function () {
            return new class implements Responsable
            {
                public function toResponse($request)
                {
                    return new JsonResponse(['foo' => 'bar']);
                }
            };
        });

        $user = User::forceCreate([
            'name' => 'Taylor Otwell',
            'email' => 'taylor@laravel.com',
            'password' => 'secret',
            'email_verified_at' => now()
        ]);

        $response = $this->actingAs($user)->get('/teams/create');

        $response->assertOk();

        $response->assertExactJson(['foo' => 'bar']);

        $team = ( new CreateTeam() )->create($user, ['name' => 'Test Team']);

        $response = $this->actingAs($user)->get('/teams/'.$team->id);

        $response->assertOk();

        $response->assertExactJson(['foo' => 'bar']);
    }
}

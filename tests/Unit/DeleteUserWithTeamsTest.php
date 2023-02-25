<?php

namespace Laravel\Jetstream\Tests\Unit;

use App\Actions\Jetstream\DeleteTeam;
use App\Actions\Jetstream\DeleteUser;
use App\Models\Team;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\TeamPolicy;
use Laravel\Jetstream\Tests\Fixtures\User;

beforeEach(function () {
    Gate::policy(Team::class, TeamPolicy::class);
    Jetstream::useUserModel(User::class);

    $this->artisan('migrate', ['--database' => 'testbench'])->run();

    Schema::create('personal_access_tokens', function ($table) {
        $table->id();
        $table->foreignId('tokenable_id');
        $table->string('tokenable_type');
    });
});

test('user can be deleted', function (): void {
    $team = createTeam(Str::random(10), Str::random(10).'@laravel.com');
    $otherTeam = createTeam(Str::random(10), Str::random(10).'@laravel.com');

    $otherTeam->users()->attach($team->owner, ['role' => null]);

    $this->assertSame(2, DB::table('teams')->count());
    $this->assertSame(1, DB::table('team_user')->count());

    copy(__DIR__.'/../../stubs/app/Actions/Jetstream/DeleteUserWithTeams.php', $fixture = __DIR__.'/../Fixtures/DeleteUser.php');

    require $fixture;

    $action = new DeleteUser(new DeleteTeam);

    $action->delete($team->owner);

    $this->assertNull($team->owner->fresh());
    $this->assertSame(1, DB::table('teams')->count());
    $this->assertSame(0, DB::table('team_user')->count());

    @unlink($fixture);
});

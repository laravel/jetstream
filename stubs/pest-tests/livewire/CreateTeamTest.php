<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\CreateTeamForm;
use Livewire\Livewire;

test('teams can be created', function ()
{
    $this->actingAs($user = User::factory()->withPersonalTeam()->create());

    Livewire::test(CreateTeamForm::class)
                ->set(['state' => ['name' => 'Test Team']])
                ->call('createTeam');

    $this->assertCount(2, $user->fresh()->ownedTeams);
    $this->assertEquals('Test Team', $user->fresh()->ownedTeams()->latest('id')->first()->name);
});

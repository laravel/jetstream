<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\CompanyEmployeeManager;
use Livewire\Livewire;

test('company employee roles can be updated', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $user->currentCompany->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                    ->set('managingRoleFor', $otherUser)
                    ->set('currentRole', 'editor')
                    ->call('updateRole');

    expect($otherUser->fresh()->hasCompanyRole(
        $user->currentCompany->fresh(), 'editor'
    ))->toBeTrue();
});

test('only company owner can update company employee roles', function () {
    $user = User::factory()->withPersonalCompany()->create();

    $user->currentCompany->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                    ->set('managingRoleFor', $otherUser)
                    ->set('currentRole', 'editor')
                    ->call('updateRole')
                    ->assertStatus(403);

    expect($otherUser->fresh()->hasCompanyRole(
        $user->currentCompany->fresh(), 'admin'
    ))->toBeTrue();
});

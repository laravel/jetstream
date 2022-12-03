<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\CompanyEmployeeManager;
use Livewire\Livewire;

test('company employees can be removed from companies', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $user->currentCompany->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                    ->set('companyEmployeeIdBeingRemoved', $otherUser->id)
                    ->call('removeCompanyEmployee');

    expect($user->currentCompany->fresh()->users)->toHaveCount(0);
});

test('only company owner can remove company employees', function () {
    $user = User::factory()->withPersonalCompany()->create();

    $user->currentCompany->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                    ->set('companyEmployeeIdBeingRemoved', $user->id)
                    ->call('removeCompanyEmployee')
                    ->assertStatus(403);
});

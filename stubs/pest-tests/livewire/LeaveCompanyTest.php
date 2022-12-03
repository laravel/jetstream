<?php

use App\Models\User;
use Laravel\Jetstream\Http\Livewire\CompanyEmployeeManager;
use Livewire\Livewire;

test('users can leave companies', function () {
    $user = User::factory()->withPersonalCompany()->create();

    $user->currentCompany->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'admin']
    );

    $this->actingAs($otherUser);

    $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                    ->call('leaveCompany');

    expect($user->currentCompany->fresh()->users)->toHaveCount(0);
});

test('company owners cant leave their own company', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                    ->call('leaveCompany')
                    ->assertHasErrors(['company']);

    expect($user->currentCompany->fresh())->not->toBeNull();
});

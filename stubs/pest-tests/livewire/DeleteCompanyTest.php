<?php

use App\Models\Company;
use App\Models\User;
use Laravel\Jetstream\Http\Livewire\DeleteCompanyForm;
use Livewire\Livewire;

test('companies can be deleted', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $user->ownedCompanies()->save($company = Company::factory()->make([
        'personal_company' => false,
    ]));

    $company->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'test-role']
    );

    $component = Livewire::test(DeleteCompanyForm::class, ['company' => $company->fresh()])
                            ->call('deleteCompany');

    expect($company->fresh())->toBeNull();
    expect($otherUser->fresh()->companies)->toHaveCount(0);
});

test('personal companies cant be deleted', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $component = Livewire::test(DeleteCompanyForm::class, ['company' => $user->currentCompany])
                            ->call('deleteCompany')
                            ->assertHasErrors(['company']);

    expect($user->currentCompany->fresh())->not->toBeNull();
});

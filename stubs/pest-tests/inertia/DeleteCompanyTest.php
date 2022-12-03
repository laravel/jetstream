<?php

use App\Models\Company;
use App\Models\User;

test('companies can be deleted', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $user->ownedCompanies()->save($company = Company::factory()->make([
        'personal_company' => false,
    ]));

    $company->users()->attach(
        $otherUser = User::factory()->create(), ['role' => 'test-role']
    );

    $response = $this->delete('/companies/'.$company->id);

    expect($company->fresh())->toBeNull();
    expect($otherUser->fresh()->companies)->toHaveCount(0);
});

test('personal companies cant be deleted', function () {
    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $response = $this->delete('/companies/'.$user->currentCompany->id);

    expect($user->currentCompany->fresh())->not->toBeNull();
});

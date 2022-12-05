<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\CompanyEmployeeManager;
use Laravel\Jetstream\Mail\CompanyInvitation;
use Livewire\Livewire;

test('company employees can be invited to company', function () {
    Mail::fake();

    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                    ->set('addCompanyEmployeeForm', [
                        'email' => 'test@example.com',
                        'role' => 'admin',
                    ])->call('addCompanyEmployee');

    Mail::assertSent(CompanyInvitation::class);

    expect($user->currentCompany->fresh()->companyInvitations)->toHaveCount(1);
})->skip(function () {
    return ! Features::sendsCompanyInvitations();
}, 'Company invitations not enabled.');

test('company employee invitations can be cancelled', function () {
    Mail::fake();

    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    // Add the company employee...
    $component = Livewire::test(CompanyEmployeeManager::class, ['company' => $user->currentCompany])
                    ->set('addCompanyEmployeeForm', [
                        'email' => 'test@example.com',
                        'role' => 'admin',
                    ])->call('addCompanyEmployee');

    $invitationId = $user->currentCompany->fresh()->companyInvitations->first()->id;

    // Cancel the company invitation...
    $component->call('cancelCompanyInvitation', $invitationId);

    expect($user->currentCompany->fresh()->companyInvitations)->toHaveCount(0);
})->skip(function () {
    return ! Features::sendsCompanyInvitations();
}, 'Company invitations not enabled.');

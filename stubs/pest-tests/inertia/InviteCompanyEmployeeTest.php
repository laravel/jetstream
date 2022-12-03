<?php

use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Mail\CompanyInvitation;

test('company employees can be invited to company', function () {
    Mail::fake();

    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $response = $this->post('/companies/'.$user->currentCompany->id.'/employees', [
        'email' => 'test@example.com',
        'role' => 'admin',
    ]);

    Mail::assertSent(CompanyInvitation::class);

    expect($user->currentCompany->fresh()->companyInvitations)->toHaveCount(1);
})->skip(function () {
    return ! Features::sendsCompanyInvitations();
}, 'Company invitations not enabled.');

test('company employee invitations can be cancelled', function () {
    Mail::fake();

    $this->actingAs($user = User::factory()->withPersonalCompany()->create());

    $invitation = $user->currentCompany->companyInvitations()->create([
        'email' => 'test@example.com',
        'role' => 'admin',
    ]);

    $response = $this->delete('/company-invitations/'.$invitation->id);

    expect($user->currentCompany->fresh()->companyInvitations)->toHaveCount(0);
})->skip(function () {
    return ! Features::sendsCompanyInvitations();
}, 'Company invitations not enabled.');

<?php

use App\Models\User;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Http\Livewire\UpdateProfileInformationForm;
use Livewire\Livewire;

test('current profile information is available', function () {
    $this->actingAs($user = User::factory()->create());

    $component = Livewire::test(UpdateProfileInformationForm::class);

    expect($component->state['name'])->toEqual($user->name);
    expect($component->state['email'])->toEqual($user->email);
})->skip(function () {
    return ! Features::enabled(Features::updateProfileInformation());
}, 'Update profile information is not enabled.');

test('profile information can be updated', function () {
    $this->actingAs($user = User::factory()->create());

    Livewire::test(UpdateProfileInformationForm::class)
            ->set('state', ['name' => 'Test Name', 'email' => 'test@example.com'])
            ->call('updateProfileInformation');

    expect($user->fresh())
        ->name->toEqual('Test Name')
        ->email->toEqual('test@example.com');
})->skip(function () {
    return ! Features::enabled(Features::updateProfileInformation());
}, 'Update profile information is not enabled.');

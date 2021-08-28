<?php

use App\Models\User;
use Laravel\Jetstream\Features;
use Laravel\Jetstream\Http\Livewire\DeleteUserForm;
use Livewire\Livewire;

test('user accounts can be deleted', function () {
    if (! Features::hasAccountDeletionFeatures()) {
        return $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    $component = Livewire::test(DeleteUserForm::class)
                    ->set('password', 'password')
                    ->call('deleteUser');

    expect($user->fresh())->toBeNull();
});

test('correct_password_must_be_provided_before_account_can_be_deleted', function () {
    if (! Features::hasAccountDeletionFeatures()) {
        return $this->markTestSkipped('Account deletion is not enabled.');
    }

    $this->actingAs($user = User::factory()->create());

    Livewire::test(DeleteUserForm::class)
                    ->set('password', 'wrong-password')
                    ->call('deleteUser')
                    ->assertHasErrors(['password']);

    expect($user->fresh())->not->toBeNull();
});

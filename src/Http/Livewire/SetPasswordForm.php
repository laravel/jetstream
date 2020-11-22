<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Support\Facades\Auth;
use Laravel\Jetstream\Contracts\SetsUserPasswords;
use Livewire\Component;

class SetPasswordForm extends Component
{
    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [
        'password' => '',
        'password_confirmation' => '',
    ];

    /**
     * Update the user's password.
     *
     * @param  \Laravel\Jetstream\Contracts\SetsUserPasswords  $setter
     * @return void
     */
    public function setPassword(SetsUserPasswords $setter)
    {
        $this->resetErrorBag();

        $setter->set(Auth::user(), $this->state);

        $this->state = [
            'password' => '',
            'password_confirmation' => '',
        ];

        // Show banner notification.
        session()->put('flash.bannerStyle', 'success');
        session()->put('flash.banner', 'Password saved.');

        return redirect(route('profile.show'));
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('profile.set-password-form');
    }
}

<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Laravel\Fortify\Actions\ConfirmPassword;
use Laravel\Jetstream\Contracts\DeletesUsers;

class CurrentUserController extends Controller
{
    /**
     * Delete the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, StatefulGuard $guard)
    {
        $confirmed = app(ConfirmPassword::class)(
            $guard, $request->user(), $request->password
        );

        if (! $confirmed) {
            throw ValidationException::withMessages([
                'password' => __('The password is incorrect.'),
            ]);
        }

        app(DeletesUsers::class)->delete($request->user()->fresh());

        $guard->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Inertia::location(url('/'));
    }
}

<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Laravel\Jetstream\Contracts\DeletesUsers;

class CurrentUserController extends Controller
{
    /**
     * Delete the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $auth
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, StatefulGuard $auth)
    {
        if (! Hash::check($request->password, $request->user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ])->errorBag('deleteUser');
        }

        app(DeletesUsers::class)->delete($request->user()->fresh());

        $auth->logout();

        return response('', 409)->header('X-Inertia-Location', url('/'));
    }
}

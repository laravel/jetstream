<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Jetstream\Contracts\SetsUserPasswords;

class PasswordController extends Controller
{
    /**
     * Set a users password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Jetstream\Contracts\SetsUserPasswords  $setter
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request, SetsUserPasswords $setter)
    {
        $setter->set($request->user(), $request->only(['password', 'password_confirmation']));

        return back(303);
    }
}

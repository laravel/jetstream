<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Laravel\Jetstream\Contracts\DeletesUsers;

class CurrentUserController extends Controller
{
    /**
     * Delete the current user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $auth
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, StatefulGuard $auth)
    {
        $request->validate([
            'password' => 'password',
        ]);

        app(DeletesUsers::class)->delete($request->user()->fresh());

        $auth->logout();

        return Inertia::location(url('/'));
    }
}

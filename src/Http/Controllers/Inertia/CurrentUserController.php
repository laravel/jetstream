<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
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
        app(DeletesUsers::class)->delete($request->user()->fresh());

        $auth->logout();

        return response('', 409)->header('X-Inertia-Location', url('/'));
    }
}

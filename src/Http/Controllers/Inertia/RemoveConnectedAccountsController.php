<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class RemoveConnectedAccountsController extends Controller
{
    /**
     * Delete a connected account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $connectedAccountId
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, $connectedAccountId)
    {
        $this->removeConnectedAccount($request, $connectedAccountId);

        return back(303);
    }

    /**
     * Remove a connected account.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $id
     * @return void
     */
    public function removeConnectedAccount(Request $request, $id)
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::table('connected_accounts')
            ->where('user_id', $request->user()->getKey())
            ->where('id', $id)
            ->delete();
    }
}

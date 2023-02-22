<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmPassword;

class OtherBrowserSessionsController extends Controller
{
    /**
     * Log out from other browser sessions.
     *
     * @throws AuthenticationException
     */
    public function destroy(Request $request, StatefulGuard $guard): RedirectResponse
    {
        $confirmed = app(ConfirmPassword::class)(
            $guard, $request->user(), $request->password
        );

        if (! $confirmed) {
            throw ValidationException::withMessages([
                'password' => __('The password is incorrect.'),
            ]);
        }

        $guard->logoutOtherDevices($request->password);

        $this->deleteOtherSessionRecords($request);

        return back(303);
    }

    /**
     * Delete the other browser session records from storage.
     */
    protected function deleteOtherSessionRecords(Request $request): void
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', $request->user()->getAuthIdentifier())
            ->where('id', '!=', $request->session()->getId())
            ->delete();
    }
}

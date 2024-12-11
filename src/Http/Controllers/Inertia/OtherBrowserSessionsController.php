<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Actions\ConfirmPassword;

class OtherBrowserSessionsController extends Controller
{
    /**
     * Log out from other browser sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return \Illuminate\Http\RedirectResponse
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

        $guard->logoutOtherDevices($request->password);

        $this->deleteOtherSessionRecords($request, $guard);

        return back(303);
    }

    /**
     * Delete the other browser session records from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    protected function deleteOtherSessionRecords(Request $request, StatefulGuard $guard)
    {
        $user = $request->user();

        if (config('session.driver') === 'database') {
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                ->where('user_id', $user->getAuthIdentifier())
                ->where('id', '!=', $request->session()->getId())
                ->delete();
        }

        if (config('session.driver') === 'redis') {
            $redis = Redis::connection();
            $authKey = env('AUTH_REDIS_KEY', '_auth_redis_key_').$user->getAuthIdentifier();
            $sessionKeys = $redis->lrange($authKey, 0, -1);
            $currentSessionId = $request->session()->getId();

            // Delete all user sessions except the current one
            foreach ($sessionKeys as $key) {
                $session = json_decode($key);
                if ($session->id !== $currentSessionId) {
                    $redis->del(config('cache.prefix').$session->id);
                }
            }

            // Regenerate remember_token
            $guard->logout();
            $guard->login($user, Cookie::has($guard->getRecallerName()));

            // Save current session
            $redis->del($authKey);
            $redis->rpush($authKey, json_encode([
                'id' => $currentSessionId,
                'last_activity' => now()->timestamp,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]));
        }
    }
}

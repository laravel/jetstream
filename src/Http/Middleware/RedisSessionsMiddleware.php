<?php

namespace Laravel\Jetstream\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redis;

class RedisSessionsMiddleware
{
    public function handle($request, Closure $next)
    {
        if (config('session.driver') !== 'redis') {
            return $next($request);
        }

        [$currentSessionId, $userId] = $this->getSessionAndUserId($request);

        // Process the request
        $response = $next($request);

        [$newSessionId, $newUserId] = $this->getSessionAndUserId($request);

        // Use newUserId if it's not null
        if ($newUserId !== null) {
            $userId = $newUserId;
        }

        // Check if the session ID has changed
        if ($currentSessionId !== $newSessionId && $userId) {
            $this->updateUserSessions($userId, $currentSessionId, $newSessionId, $request);
        }

        return $response;
    }

    private function getSessionAndUserId($request)
    {
        if (Auth::check()) {
            return [$request->session()->getId(), Auth::id()];
        }

        return [null, null];
    }

    private function updateUserSessions($userId, $currentSessionId, $newSessionId, $request)
    {
        $authKey = env('AUTH_REDIS_KEY', '_auth_redis_key_').$userId;

        // Load current list of sessions
        $sessions = Redis::lrange($authKey, 0, -1);

        // Delete current list of sessions
        Redis::del($authKey);

        // Form a new list of sessions
        foreach ($sessions as $session) {
            $sessionData = json_decode($session, true);
            if ($sessionData['id'] !== $currentSessionId) {
                Redis::rpush($authKey, $session);
            }
        }

        // Add new session
        if ($newSessionId) {
            Redis::rpush($authKey, json_encode([
                'id' => $newSessionId,
                'last_activity' => now()->timestamp,
                'user_agent' => $request->userAgent(),
                'ip_address' => $request->ip(),
            ]));
        }
    }
}

<?php

namespace Laravel\Jetstream\Http\Middleware;

use Illuminate\Contracts\Auth\Factory;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Session\Middleware\AuthenticateSession as BaseAuthenticateSession;

class AuthenticateSession extends BaseAuthenticateSession
{
    /**
     * Get the guard instance that should be used by the middleware.
     */
    protected function guard(): Guard|Factory
    {
        return app(StatefulGuard::class);
    }
}

<?php

namespace Laravel\Jetstream;

use Illuminate\Http\RedirectResponse;

trait RedirectsActions
{
    /**
     * Get the redirect response for the given action.
     *
     * @param  mixed  $action
     * @return \Illuminate\Http\RedirectResponse
     */
    public function redirectPath($action)
    {
        if (method_exists($action, 'redirectTo')) {
            $response = $action->redirectTo();
        } else {
            $response = property_exists($action, 'redirectTo')
                                ? $action->redirectTo
                                : config('fortify.home');
        }

        return $response instanceof RedirectResponse ? $response : redirect($response);
    }
}

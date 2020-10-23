<?php

namespace Laravel\Jetstream;

trait RedirectsActions
{
    /**
     * Get the redirect path for the action.
     *
     * @param $action
     * @return string
     */
    public function redirectPath($action)
    {
        if (method_exists($action, 'redirectTo')) {
            return $action->redirectTo();
        }

        return property_exists($action, 'redirectTo') ? $action->redirectTo : config('fortify.home');
    }
}

<?php

namespace Laravel\Jetstream\Http\Responses;

use Illuminate\Contracts\Support\Responsable;
use Laravel\Jetstream\Contracts\ApiIndexViewResponse;
use Laravel\Jetstream\Contracts\ProfileShowViewResponse;
use Laravel\Jetstream\Contracts\TeamsCreateViewResponse;
use Laravel\Jetstream\Contracts\TeamsShowViewResponse;

class SimpleViewResponse implements
    ApiIndexViewResponse,
    ProfileShowViewResponse,
    TeamsCreateViewResponse,
    TeamsShowViewResponse
{
    /**
     * The name of the view or the callable used to generate the view.
     *
     * @var callable|string
     */
    protected $view;

    /**
     * Create a new response instance.
     *
     * @param  callable|string  $view
     * @return void
     */
    public function __construct($view)
    {
        $this->view = $view;
    }

    /**
     * Create an HTTP response that represents the object.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function toResponse($request)
    {
        if (! is_callable($this->view) || is_string($this->view)) {
            return view($this->view, ['request' => $request]);
        }

        $response = call_user_func($this->view, $request);

        if ($response instanceof Responsable) {
            return $response->toResponse($request);
        }

        return $response;
    }
}

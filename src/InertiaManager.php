<?php

namespace Laravel\Jetstream;

use Illuminate\Http\Request;
use Inertia\Inertia;

class InertiaManager
{
    /**
     * The registered rendering callbacks.
     *
     * @var array
     */
    protected $renderingCallbacks = [];

    /**
     * Render the given Inertia page.
     *
     * @return \Inertia\Response
     */
    public function render(Request $request, string $page, array $data = [])
    {
        if (isset($this->renderingCallbacks[$page])) {
            foreach ($this->renderingCallbacks[$page] as $callback) {
                $data = $callback($request, $data);
            }
        }

        return Inertia::render($page, $data);
    }

    /**
     * Register a rendering callback.
     *
     * @return $this
     */
    public function whenRendering(string $page, callable $callback)
    {
        $this->renderingCallbacks[$page][] = $callback;

        return $this;
    }
}

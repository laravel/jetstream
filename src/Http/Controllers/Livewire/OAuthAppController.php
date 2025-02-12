<?php

namespace Laravel\Jetstream\Http\Controllers\Livewire;

use Illuminate\Routing\Controller;

class OAuthAppController extends Controller
{
    /**
     * Show the user API token screen.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        return view('oauth.index');
    }
}

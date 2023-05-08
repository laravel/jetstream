<?php

namespace Laravel\Jetstream\Http\Controllers\Livewire;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ApiTokenController extends Controller
{
    /**
     * Show the user API token screen.
     *
     * @return \Illuminate\View\View
     */
    public function index(Request $request)
    {
        return view('api.index', [
            'request' => $request,
            'user' => $request->user(),
        ]);
    }
}

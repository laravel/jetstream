<?php

namespace Laravel\Jetstream\Http\Controllers\Livewire;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;

class ApiTokenController extends Controller
{
    /**
     * Show the user API token screen.
     */
    public function index(Request $request): View
    {
        return view('api.index', [
            'request' => $request,
            'user' => $request->user(),
        ]);
    }
}

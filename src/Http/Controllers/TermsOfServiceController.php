<?php

namespace Laravel\Jetstream\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Parsedown;

class TermsOfServiceController extends Controller
{
    /**
     * Show the terms of service for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $termsFile = file_exists(base_path('terms.'.app()->getLocale().'.md'))
                            ? base_path('terms.'.app()->getLocale().'.md')
                            : base_path('terms.md');

        return view('terms', [
            'terms' => (new Parsedown)->text(file_get_contents($termsFile)),
        ]);
    }
}
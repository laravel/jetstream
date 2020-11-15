<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use League\CommonMark\CommonMarkConverter;

class TermsOfServiceController extends Controller
{
    /**
     * Show the terms of service for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $termsFile = file_exists(base_path('terms.'.app()->getLocale().'.md'))
                            ? base_path('terms.'.app()->getLocale().'.md')
                            : base_path('terms.md');

        return Inertia::render('Terms', [
            'terms' => (new CommonMarkConverter())->convertToHtml(file_get_contents($termsFile)),
        ]);
    }
}

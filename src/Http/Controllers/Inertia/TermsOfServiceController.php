<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;
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
        $termsFile = Jetstream::localizedMarkdownPath('terms.md');

        return Inertia::render('TermsOfService', [
            'terms' => (new CommonMarkConverter())->convertToHtml(file_get_contents($termsFile)),
        ]);
    }
}

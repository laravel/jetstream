<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Laravel\Jetstream\Jetstream;

class PrivacyPolicyController extends Controller
{
    /**
     * Show the privacy policy for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        $policyFile = Jetstream::localizedMarkdownPath('policy.md');

        return Inertia::render('PrivacyPolicy', [
            'policy' => Str::markdown(file_get_contents($policyFile)),
        ]);
    }
}

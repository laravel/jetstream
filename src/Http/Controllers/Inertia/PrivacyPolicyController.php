<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;
use Laravel\Jetstream\Jetstream;

class PrivacyPolicyController extends Controller
{
    /**
     * Show the privacy policy for the application.
     */
    public function show(Request $request): Response
    {
        $policyFile = Jetstream::localizedMarkdownPath('policy.md');

        return Inertia::render('PrivacyPolicy', [
            'policy' => Str::markdown(file_get_contents($policyFile)),
        ]);
    }
}

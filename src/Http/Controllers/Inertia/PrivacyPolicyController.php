<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Inertia\Inertia;
use League\CommonMark\CommonMarkConverter;

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
        $policyFile = file_exists(base_path('policy.'.app()->getLocale().'.md'))
                            ? base_path('policy.'.app()->getLocale().'.md')
                            : base_path('policy.md');

        return Inertia::render('PrivacyPolicy', [
            'policy' => (new CommonMarkConverter())->convertToHtml(file_get_contents($policyFile)),
        ]);
    }
}

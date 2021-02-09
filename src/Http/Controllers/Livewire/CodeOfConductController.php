<?php

namespace Laravel\Jetstream\Http\Controllers\Livewire;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Laravel\Jetstream\Jetstream;
use League\CommonMark\CommonMarkConverter;
use League\CommonMark\Environment;
use League\CommonMark\Extension\GithubFlavoredMarkdownExtension;

class CodeOfConductController extends Controller
{
    /**
     * Show the code of conduct for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\View\View
     */
    public function show(Request $request)
    {
        $conductFile = Jetstream::localizedMarkdownPath('conduct.md');

        $environment = Environment::createCommonMarkEnvironment();
        $environment->addExtension(new GithubFlavoredMarkdownExtension());

        return view('conduct', [
            'conduct' => (new CommonMarkConverter([], $environment))->convertToHtml(file_get_contents($conductFile)),
        ]);
    }
}

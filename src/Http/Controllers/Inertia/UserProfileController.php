<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Jenssegers\Agent\Agent;
use Laravel\Jetstream\Jetstream;

class UserProfileController extends Controller
{
    /**
     * Show the general profile settings screen.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Inertia\Response
     */
    public function show(Request $request)
    {
        return Jetstream::inertia()->render($request, 'Profile/Show', [
            'sessions' => $this->sessions($request)->all(),
            'connectedAccounts' => $request->user()->connectedAccounts
                ->map(function ($account) {
                    return (object) [
                        'id' => $account->id,
                        'provider_name' => $account->provider_name,
                        'created_at' => (new \DateTime($account->created_at))->format('d/m/Y H:i'),
                    ];
                }),
            'socialiteProviders' => [
                'facebook' => Jetstream::hasSocialiteSupportFor('facebook'),
                'google' => Jetstream::hasSocialiteSupportFor('google'),
                'twitter' => Jetstream::hasSocialiteSupportFor('twitter'),
                'linkedin' => Jetstream::hasSocialiteSupportFor('linkedin'),
                'github' => Jetstream::hasSocialiteSupportFor('github'),
                'gitlab' => Jetstream::hasSocialiteSupportFor('gitlab'),
                'bitbucket' => Jetstream::hasSocialiteSupportFor('bitbucket'),
            ],
        ]);
    }

    /**
     * Get the current sessions.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Support\Collection
     */
    public function sessions(Request $request)
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::table(config('session.table', 'sessions'))
                    ->where('user_id', $request->user()->getAuthIdentifier())
                    ->orderBy('last_activity', 'desc')
                    ->get()
        )->map(function ($session) use ($request) {
            $agent = $this->createAgent($session);

            return (object) [
                'agent' => [
                    'is_desktop' => $agent->isDesktop(),
                    'platform' => $agent->platform(),
                    'browser' => $agent->browser(),
                ],
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === $request->session()->getId(),
                'last_active' => Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
            ];
        });
    }

    /**
     * Create a new agent instance from the given session.
     *
     * @param  mixed  $session
     * @return \Jenssegers\Agent\Agent
     */
    protected function createAgent($session)
    {
        return tap(new Agent, function ($agent) use ($session) {
            $agent->setUserAgent($session->user_agent);
        });
    }
}

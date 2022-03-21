<?php

namespace Laravel\Jetstream\Http\Livewire;

use Illuminate\Contracts\Auth\StatefulGuard;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use Jenssegers\Agent\Agent;
use Livewire\Component;

class LogoutOtherBrowserSessionsForm extends Component
{
    /**
     * Indicates if logout is being confirmed.
     *
     * @var bool
     */
    public $confirmingLogout = false;

    /**
     * The user's current password.
     *
     * @var string
     */
    public $password = '';

    /**
     * Confirm that the user would like to log out from other browser sessions.
     *
     * @return void
     */
    public function confirmLogout()
    {
        $this->password = '';

        $this->dispatchBrowserEvent('confirming-logout-other-browser-sessions');

        $this->confirmingLogout = true;
    }

    /**
     * Log out from other browser sessions.
     *
     * @param  \Illuminate\Contracts\Auth\StatefulGuard  $guard
     * @return void
     */
    public function logoutOtherBrowserSessions(StatefulGuard $guard)
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        $this->resetErrorBag();

        if (! Hash::check($this->password, Auth::user()->password)) {
            throw ValidationException::withMessages([
                'password' => [__('This password does not match our records.')],
            ]);
        }

        $guard->logoutOtherDevices($this->password);

        $this->deleteOtherSessionRecords();

        request()->session()->put([
            'password_hash_'.Auth::getDefaultDriver() => Auth::user()->getAuthPassword(),
        ]);

        $this->confirmingLogout = false;

        $this->emit('loggedOut');
    }

    /**
     * Delete the other browser session records from storage.
     *
     * @return void
     */
    protected function deleteOtherSessionRecords()
    {
        if (config('session.driver') !== 'database') {
            return;
        }

        DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
            ->where('user_id', Auth::user()->getAuthIdentifier())
            ->where('id', '!=', request()->session()->getId())
            ->delete();
    }

    /**
     * Get the current sessions.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getSessionsProperty()
    {
        if (config('session.driver') !== 'database') {
            return collect();
        }

        return collect(
            DB::connection(config('session.connection'))->table(config('session.table', 'sessions'))
                    ->where('user_id', Auth::user()->getAuthIdentifier())
                    ->orderBy('last_activity', 'desc')
                    ->get()
        )->map(function ($session) {
            return (object) [
                'agent' => $this->createAgent($session),
                'ip_address' => $session->ip_address,
                'is_current_device' => $session->id === request()->session()->getId(),
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

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('profile.logout-other-browser-sessions-form');
    }
}

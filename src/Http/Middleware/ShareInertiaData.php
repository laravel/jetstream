<?php

namespace Laravel\Jetstream\Http\Middleware;

use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Session;
use Inertia\Inertia;
use Laravel\Fortify\Features;
use Laravel\Jetstream\Jetstream;

class ShareInertiaData
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  callable  $next
     * @return \Illuminate\Http\Response
     */
    public function handle($request, $next)
    {
        Inertia::share(array_filter([
            'jetstream' => function () use ($request) {
                $user = $request->user();

                return [
                    'canCreateTeams' => $user &&
                                        Jetstream::userHasTeamFeatures($user) &&
                                        Gate::forUser($user)->check('create', Jetstream::newTeamModel()),
                    'canCreateCompanies' => $user &&
                                        Jetstream::userHasCompanyFeatures($user) &&
                                        Gate::forUser($user)->check('create', Jetstream::newCompanyModel()),
                    'canManageTwoFactorAuthentication' => Features::canManageTwoFactorAuthentication(),
                    'canUpdatePassword' => Features::enabled(Features::updatePasswords()),
                    'canUpdateProfileInformation' => Features::canUpdateProfileInformation(),
                    'hasEmailVerification' => Features::enabled(Features::emailVerification()),
                    'flash' => $request->session()->get('flash', []),
                    'hasAccountDeletionFeatures' => Jetstream::hasAccountDeletionFeatures(),
                    'hasApiFeatures' => Jetstream::hasApiFeatures(),
                    'hasTeamFeatures' => Jetstream::hasTeamFeatures(),
                    'hasCompanyFeatures' => Jetstream::hasCompanyFeatures(),
                    'hasTermsAndPrivacyPolicyFeature' => Jetstream::hasTermsAndPrivacyPolicyFeature(),
                    'managesProfilePhotos' => Jetstream::managesProfilePhotos(),
                ];
            },
            'user' => function () use ($request) {
                if (! $user = $request->user()) {
                    return;
                }

                $userHasTeamFeatures = Jetstream::userHasTeamFeatures($user);
                $userHasCompanyFeatures = Jetstream::userHasCompanyFeatures($user);

                if ($user && $userHasTeamFeatures) {
                    $user->currentTeam;
                }

                if ($user && $userHasCompanyFeatures) {
                    $user->currentCompany;

                return array_merge($user->toArray(), array_filter([
                    'all_teams' => $userHasTeamFeatures ? $user->allTeams()->values() : null,
                    'all_companies' => $userHasCompanyFeatures ? $user->allCompanies()->values() : null,
                ]), [
                    'two_factor_enabled' => ! is_null($user->two_factor_secret),
                ]);
            }},
            'errorBags' => function () {
                return collect(optional(Session::get('errors'))->getBags() ?: [])->mapWithKeys(function ($bag, $key) {
                    return [$key => $bag->messages()];
                })->all();
            },
        ]));

        return $next($request);
    }
}

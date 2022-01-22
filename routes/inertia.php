<?php

use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\CurrentTeamController;
use Laravel\Jetstream\Http\Controllers\Inertia\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Inertia\CurrentUserController;
use Laravel\Jetstream\Http\Controllers\Inertia\OtherBrowserSessionsController;
use Laravel\Jetstream\Http\Controllers\Inertia\PrivacyPolicyController;
use Laravel\Jetstream\Http\Controllers\Inertia\ProfilePhotoController;
use Laravel\Jetstream\Http\Controllers\Inertia\TeamController;
use Laravel\Jetstream\Http\Controllers\Inertia\TeamMemberController;
use Laravel\Jetstream\Http\Controllers\Inertia\TermsOfServiceController;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;
use Laravel\Jetstream\Http\Controllers\TeamInvitationController;
use Laravel\Jetstream\Jetstream;

Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () {
    if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
        Route::get('/terms-of-service', [TermsOfServiceController::class, 'show'])->name('terms.show');
        Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])->name('policy.show');
    }

    $authMiddleware = config('jetstream.guard')
            ? 'auth:'.config('jetstream.guard')
            : 'auth';

    Route::group(['middleware' => [$authMiddleware, 'verified']], function () {
        // User & Profile...
        Route::get('/user/profile', [UserProfileController::class, 'show'])
                    ->name('profile.show');

        Route::delete('/user/other-browser-sessions', [OtherBrowserSessionsController::class, 'destroy'])
                    ->name('other-browser-sessions.destroy');

        Route::delete('/user/profile-photo', [ProfilePhotoController::class, 'destroy'])
                    ->name('current-user-photo.destroy');

        if (Jetstream::hasAccountDeletionFeatures()) {
            Route::delete('/user', [CurrentUserController::class, 'destroy'])
                        ->name('current-user.destroy');
        }

        // API...
        if (Jetstream::hasApiFeatures()) {
            Route::controller(ApiTokenController::class)->name('api-tokens.')->group(function () {
                Route::get('/user/api-tokens', 'index')->name('index');
                Route::post('/user/api-tokens', 'store')->name('store');
                Route::put('/user/api-tokens/{token}', 'update')->name('update');
                Route::delete('/user/api-tokens/{token}', 'destroy')->name('destroy');
            });
        }

        // Teams...
        if (Jetstream::hasTeamFeatures()) {
            Route::controller(TeamController::class)->name('teams.')->group(function () {
                Route::get('/teams/create', 'create')->name('create');
                Route::post('/teams', 'store')->name('store');
                Route::get('/teams/{team}', 'show')->name('show');
                Route::put('/teams/{team}', 'update')->name('update');
                Route::delete('/teams/{team}', 'destroy')->name('destroy');
            });

            Route::put('/current-team', [CurrentTeamController::class, 'update'])->name('current-team.update');

            Route::controller(TeamMemberController::class)->name('team-members.')->group(function () {
                Route::post('/teams/{team}/members', 'store')->name('store');
                Route::put('/teams/{team}/members/{user}', 'update')->name('update');
                Route::delete('/teams/{team}/members/{user}', 'destroy')->name('destroy');
            });

            Route::get('/team-invitations/{invitation}', [TeamInvitationController::class, 'accept'])
                        ->middleware(['signed'])
                        ->name('team-invitations.accept');

            Route::delete('/team-invitations/{invitation}', [TeamInvitationController::class, 'destroy'])
                        ->name('team-invitations.destroy');
        }
    });
});

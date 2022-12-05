<?php

use Illuminate\Support\Facades\Route;
use Laravel\Jetstream\Http\Controllers\CurrentCompanyController;
use Laravel\Jetstream\Http\Controllers\Inertia\ApiTokenController;
use Laravel\Jetstream\Http\Controllers\Inertia\CurrentUserController;
use Laravel\Jetstream\Http\Controllers\Inertia\OtherBrowserSessionsController;
use Laravel\Jetstream\Http\Controllers\Inertia\PrivacyPolicyController;
use Laravel\Jetstream\Http\Controllers\Inertia\ProfilePhotoController;
use Laravel\Jetstream\Http\Controllers\Inertia\CompanyController;
use Laravel\Jetstream\Http\Controllers\Inertia\CompanyEmployeeController;
use Laravel\Jetstream\Http\Controllers\Inertia\TermsOfServiceController;
use Laravel\Jetstream\Http\Controllers\Inertia\UserProfileController;
use Laravel\Jetstream\Http\Controllers\CompanyInvitationController;
use Laravel\Jetstream\Jetstream;

Route::group(['middleware' => config('jetstream.middleware', ['web'])], function () {
    if (Jetstream::hasTermsAndPrivacyPolicyFeature()) {
        Route::get('/terms-of-service', [TermsOfServiceController::class, 'show'])->name('terms.show');
        Route::get('/privacy-policy', [PrivacyPolicyController::class, 'show'])->name('policy.show');
    }

    $authMiddleware = config('jetstream.guard')
            ? 'auth:'.config('jetstream.guard')
            : 'auth';

    $authSessionMiddleware = config('jetstream.auth_session', false)
            ? config('jetstream.auth_session')
            : null;

    Route::group(['middleware' => array_values(array_filter([$authMiddleware, $authSessionMiddleware]))], function () {
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

        Route::group(['middleware' => 'verified'], function () {
            // API...
            if (Jetstream::hasApiFeatures()) {
                Route::get('/user/api-tokens', [ApiTokenController::class, 'index'])->name('api-tokens.index');
                Route::post('/user/api-tokens', [ApiTokenController::class, 'store'])->name('api-tokens.store');
                Route::put('/user/api-tokens/{token}', [ApiTokenController::class, 'update'])->name('api-tokens.update');
                Route::delete('/user/api-tokens/{token}', [ApiTokenController::class, 'destroy'])->name('api-tokens.destroy');
            }

            // Companies...
            if (Jetstream::hasCompanyFeatures()) {
                Route::get('/companies/create', [CompanyController::class, 'create'])->name('companies.create');
                Route::post('/companies', [CompanyController::class, 'store'])->name('companies.store');
                Route::get('/companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
                Route::put('/companies/{company}', [CompanyController::class, 'update'])->name('companies.update');
                Route::delete('/companies/{company}', [CompanyController::class, 'destroy'])->name('companies.destroy');
                Route::put('/current-company', [CurrentCompanyController::class, 'update'])->name('current-company.update');
                Route::post('/companies/{company}/employees', [CompanyEmployeeController::class, 'store'])->name('company-employees.store');
                Route::put('/companies/{company}/employees/{user}', [CompanyEmployeeController::class, 'update'])->name('company-employees.update');
                Route::delete('/companies/{company}/employees/{user}', [CompanyEmployeeController::class, 'destroy'])->name('company-employees.destroy');

                Route::get('/company-invitations/{invitation}', [CompanyInvitationController::class, 'accept'])
                            ->middleware(['signed'])
                            ->name('company-invitations.accept');

                Route::delete('/company-invitations/{invitation}', [CompanyInvitationController::class, 'destroy'])
                            ->name('company-invitations.destroy');
            }
        });
    });
});

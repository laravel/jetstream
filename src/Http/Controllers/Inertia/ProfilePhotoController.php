<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProfilePhotoController extends Controller
{
    /**
     * Delete the current user's profile photo.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->user()->deleteProfilePhoto();

        return back(303)->with('status', 'profile-photo-deleted');
    }
}

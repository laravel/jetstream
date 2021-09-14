<?php

namespace Laravel\Jetstream\Http\Controllers\Inertia;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class ProfilePhotoController extends Controller
{
    /**
     * Delete the current user's profile photo.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request)
    {
        $request->user()->deleteProfilePhoto();

        return back(303)->with('status', 'profile-photo-deleted');
    }
}

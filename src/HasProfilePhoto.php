<?php

namespace Laravel\Jetstream;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Jetstream\Features;

trait HasProfilePhoto
{
    /**
     * Update the user's profile photo.
     *
     * @param  \Illuminate\Http\UploadedFile  $photo
     * @return void
     */
    public function updateProfilePhoto(UploadedFile $photo)
    {
        tap($this->profile_photo_path, function ($previous) use ($photo) {
            $this->forceFill([
                'profile_photo_path' => $photo->storePublicly(
                    'profile-photos', ['disk' => $this->profilePhotoDisk()]
                ),
            ])->save();

            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }
        });
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        if (! Features::managesProfilePhotos()) {
            return;
        }

        Storage::disk($this->profilePhotoDisk())->delete($this->profile_photo_path);

        $this->forceFill([
            'profile_photo_path' => null,
        ])->save();
    }

    /**
     * Get the URL to the user's profile photo.
     *
     * @return string
     */
    public function getProfilePhotoUrlAttribute()
    {
        return $this->profile_photo_path
                    ? Storage::disk($this->profilePhotoDisk())->url($this->profile_photo_path)
                    : '';
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     * Only return the default profile photo url if Jetstream is configured to manage profile photos.
     * This reduces unnecessary web requests.
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        if (! Features::managesProfilePhotos()) {
            return '';
        }
        return 'https://ui-avatars.com/api/?name='.urlencode($this->name).'&color=7F9CF5&background=EBF4FF';
    }

    /**
     * Checks if there is a profile photo.
     *
     * @return bool
     */
    public function getHasProfilePhotoAttribute()
    {
        return $this->profile_photo_path ? true : false;
    }

    /**
     * Returns the profile tag, created out of the first letter of the first two words in the users name.
     *
     * @return string
     */
    public function getProfileTagAttribute()
    {
        $segments = Str::of($this->name)->split('/[\s ]+/');

        $tag = '';

        for ($i = 0; $i < 2; $i++) {
            if (count($segments) >= $i+1)
                $tag .= Str::of($segments[$i])->ucfirst()->substr(0,1);
        }

        return $tag;
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function profilePhotoDisk()
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : 'public';
    }
}

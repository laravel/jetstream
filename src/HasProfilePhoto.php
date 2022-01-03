<?php

namespace Laravel\Jetstream;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
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
                    : $this->defaultProfilePhotoUrl();
    }

    /**
     * Get the default profile photo URL if no profile photo has been uploaded.
     *
     * Using Gravatar with UI-Avatars as fallback. Like this, we get all avatars delivered by Gravatar with
     * the following bonus: Prevent rate-limiting (ui-avatars.com uses Cloudflare that blocks fast, while Gravatar
     * has virtually no rate-limiting), nice UI-Avatars with initials (which is currently not offered by Gravatar).
     *
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        $hash = md5(strtolower(trim($this->email)));
        $defaultImage = config('jetstream.profile_photo_fallback.default_image', 'initials');

        if ($defaultImage === 'initials') {
            $name = urlencode($this->name); // name needs to be double-urlencoded for Gravatar fallback URL
            $size = config('jetstream.profile_photo_fallback.initials.size', 64);
            $bg = config('jetstream.profile_photo_fallback.initials.bg', 'ebf4ff');
            $color = config('jetstream.profile_photo_fallback.initials.color', '7f9cf5');
            $fallback = urlencode("https://ui-avatars.com/api/{$name}/{$size}/{$bg}/{$color}");
        } else {
            $fallback = $defaultImage;
        }

        return "https://www.gravatar.com/avatar/{$hash}?d={$fallback}";
    }

    /**
     * Get the disk that profile photos should be stored on.
     *
     * @return string
     */
    protected function profilePhotoDisk()
    {
        return isset($_ENV['VAPOR_ARTIFACT_NAME']) ? 's3' : config('jetstream.profile_photo_disk', 'public');
    }
}

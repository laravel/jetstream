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
     * @return array
     */
    public function updateProfilePhoto(UploadedFile $photo)
    {
        return tap($this->profile_photo_path, function ($previous) use ($photo) {
            if ($previous) {
                Storage::disk($this->profilePhotoDisk())->delete($previous);
            }

            return [
                'profile_photo_path' => $photo->storePublicly(
                    'profile-photos', ['disk' => $this->profilePhotoDisk()]
                ),
            ];
        });
    }

    /**
     * Delete the user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        $this->removeProfilePhotoFromDisk();
        $this->setNullInToProfilePhoto();
    }

    /**
     * Remove the user's profile photo from disk.
     *
     * @return void
     */
    public function removeProfilePhotoFromDisk()
    {
        if (! Features::managesProfilePhotos()) {
            return;
        }

        if (is_null($this->profile_photo_path)) {
            return;
        }

        Storage::disk($this->profilePhotoDisk())->delete($this->profile_photo_path);
    }

    /**
     * Set null user's profile photo path.
     *
     * @return void
     */
    public function setNullInToProfilePhoto()
    {
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
     * @return string
     */
    protected function defaultProfilePhotoUrl()
    {
        $name = trim(collect(explode(' ', $this->name))->map(function ($segment) {
            return mb_substr($segment, 0, 1);
        })->join(' '));

        return 'https://ui-avatars.com/api/?name='.urlencode($name).'&color=7F9CF5&background=EBF4FF';
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

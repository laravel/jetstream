<?php

namespace Laravel\Jetstream\Tests;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\Tests\Fixtures\User;

class UpdateProfilePhotoTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();

        Jetstream::useUserModel(User::class);
    }

    public function test_profile_photo_uploads_to_public_by_default_non_vapor()
    {
        $this->migrate();

        $user = User::forceCreate([
            'name'     => 'Taylor Otwell',
            'email'    => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        Storage::fake('s3');
        Storage::fake('public');

        $user->updateProfilePhoto(UploadedFile::fake()->image('photo1.jpg'));

        Storage::disk('s3')->assertMissing($user->profile_photo_path);
        Storage::disk('public')->assertExists($user->profile_photo_path);
    }

    public function test_profile_photo_uploads_to_s3_by_default_vapor()
    {
        $this->migrate();

        $_ENV['VAPOR_ARTIFACT_NAME'] = 'fake-artifact-name';

        $user = User::forceCreate([
            'name'     => 'Taylor Otwell',
            'email'    => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        Storage::fake('s3');
        Storage::fake('public');

        $user->updateProfilePhoto(UploadedFile::fake()->image('photo1.jpg'));

        Storage::disk('s3')->assertExists($user->profile_photo_path);
        Storage::disk('public')->assertMissing($user->profile_photo_path);
    }

    public function test_profile_photo_uploads_to_s3_if_specified_non_vapor()
    {
        $this->migrate();

        config(['jetstream.profile_photo_disk' => 's3']);

        $user = User::forceCreate([
            'name'     => 'Taylor Otwell',
            'email'    => 'taylor@laravel.com',
            'password' => 'secret',
        ]);

        Storage::fake('s3');
        Storage::fake('public');

        $user->updateProfilePhoto(UploadedFile::fake()->image('photo1.jpg'));

        Storage::disk('s3')->assertExists($user->profile_photo_path);
        Storage::disk('public')->assertMissing($user->profile_photo_path);
    }

    protected function migrate()
    {
        $this->artisan('migrate', ['--database' => 'testbench'])->run();
    }
}

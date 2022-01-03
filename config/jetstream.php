<?php

use Laravel\Jetstream\Features;

return [

    /*
    |--------------------------------------------------------------------------
    | Jetstream Stack
    |--------------------------------------------------------------------------
    |
    | This configuration value informs Jetstream which "stack" you will be
    | using for your application. In general, this value is set for you
    | during installation and will not need to be changed after that.
    |
    */

    'stack' => 'inertia',

    /*
     |--------------------------------------------------------------------------
     | Jetstream Route Middleware
     |--------------------------------------------------------------------------
     |
     | Here you may specify which middleware Jetstream will assign to the routes
     | that it registers with the application. When necessary, you may modify
     | these middleware; however, this default value is usually sufficient.
     |
     */

    'middleware' => ['web'],

    /*
    |--------------------------------------------------------------------------
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of Jetstream's features are optional. You may disable the features
    | by removing them from this array. You're free to only remove some of
    | these features or you can even remove all of these if you need to.
    |
    */

    'features' => [
        // Features::termsAndPrivacyPolicy(),
        // Features::profilePhotos(),
        // Features::api(),
        // Features::teams(['invitations' => true]),
        Features::accountDeletion(),
    ],

    /*
    |--------------------------------------------------------------------------
    | Profile Photo Disk
    |--------------------------------------------------------------------------
    |
    | This configuration value determines the default disk that will be used
    | when storing profile photos for your application's users. Typically,
    | this will be the "public" disk, but you may adjust this if needed.
    |
    */

    'profile_photo_disk' => 'public',

    /*
    |--------------------------------------------------------------------------
    | Profile Photo Fallback Image
    |--------------------------------------------------------------------------
    |
    | This configuration value determines the default image that is used as a
    | fallback if the user has not stored a profile photo.
    | By default, we use Gravatar images with UI-Avatars as a fallback.
    |
    | Possible values for profile_photo_fallback.default_image:
    | mp|identicon|monsterid|wavatar|retro|robohash|blank|initials
    | (see http://gravatar.com/site/implement/images/)
    | 'initials' (default) is a special extension where we use ui-avatars.com as
    | a Gravatar fallback service to generate avatars with initials from names.
    |
    */

    'profile_photo_fallback' => [
        'default_image' => 'initials',
        'initials' => [
            'size'  => 64,
            'bg'    => 'ebf4ff',
            'color' => '7f9cf5',
        ],
    ],

];

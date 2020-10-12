<?php

use Laravel\Jetstream\Features;
use Laravel\Socialite\One\TwitterProvider;
use Laravel\Socialite\Two\GithubProvider;

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
    | Features
    |--------------------------------------------------------------------------
    |
    | Some of Jetstream's features are optional. You may disable the features
    | by removing them from this array. You're free to only remove some of
    | these features or you can even remove all of these if you need to.
    |
    */

    'features' => [
        // Features::profilePhotos(),
        // Features::api(),
        // Features::teams(),
        // Features::socialite(),
        Features::accountDeletion(),
    ],


    /*
    |--------------------------------------------------------------------------
    | Providers
    |--------------------------------------------------------------------------
    |
    | If you've opted into using Socialite, you may define the providers you
    | wish to use for OAuth1 and OAuth 2 here. You're free to extend this
    | functionality by writing your own provider.
    |
    | OAuth 1 Supported: Twitter
    |
    | OAuth 2 Supported: Bitbucket, Facebook, Github, Gitlab, Google, LinkedIn
    |
    */

    'socialite_providers' => [
        'oauth1' => [
            // TwitterProvider::class,
        ],

        'oauth2' => [
            // GithubProvider::class,
        ],
    ]
];

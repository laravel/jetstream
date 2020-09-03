<p align="center"><img src="https://laravel.com/assets/img/components/logo-jetstream.svg"></p>

<p align="center">
    <a href="https://github.com/laravel/jetstream/actions">
        <img src="https://github.com/laravel/jetstream/workflows/tests/badge.svg" alt="Build Status">
    </a>
    <a href="https://packagist.org/packages/laravel/jetstream">
        <img src="https://poser.pugx.org/laravel/jetstream/d/total.svg" alt="Total Downloads">
    </a>
    <a href="https://packagist.org/packages/laravel/jetstream">
        <img src="https://poser.pugx.org/laravel/jetstream/v/stable.svg" alt="Latest Stable Version">
    </a>
    <a href="https://packagist.org/packages/laravel/jetstream">
        <img src="https://poser.pugx.org/laravel/jetstream/license.svg" alt="License">
    </a>
</p>

- [Introduction](#introduction)
- [Installation](#installation)
- [Jetstream Features](#jetstream-features)
    - [Profile Management](#profile-management)

<a name="introduction"></a>
## Introduction

Laravel Jetstream is a beautifully designed application scaffolding for Laravel. Jetstream provides the perfect starting point for your next Laravel application and includes login, registration, email verification, two-factor authentication, session management, API support via [Laravel Sanctum](https://github.com/laravel/sanctum), and optional team management.

Jetstream is designed using [Tailwind CSS](https://tailwindcss.com) and offers your choice of [Livewire](https://laravel-livewire.com) or [Inertia](https://inertiajs.com) scaffolding.

<a name="installation"></a>
## Installation

You may use Composer to install Jetstream into your new Laravel project:

    composer require laravel/jetstream

After installing Jetstream, you should run the `jetstream:install` Artisan command. This command accepts the name of the stack you prefer (`livewire` or `inertia`). In addition, you may use the `teams` switch to enable team support:

    php artisan jetstream:install livewire

    php artisan jetstream inertia --teams

After installing your Jetstream stack, you should compile your frontend assets:

    npm install && npm run dev

#### Using The Laravel Installer

If the [Laravel installer](https://github.com/laravel/installer) is installed on your machine, you may use the `jet` switch to create a new Jetstream powered Laravel application:

    laravel new project-name --jet

<a name="jetstream-features"></a>
## Jetstream Features

Various Jetstream features may be enabled / disabled via the `features` option within your application's `jetstream` configuration file.

<a name="profile-management"></a>
### Profile Management

Jetstream includes forms that allow the user to update their name and email address, as well as upload a profile photo. The backend logic for this feature may be customized by modifying the `App\Actions\Fortify\UpdateUserProfileInformation` class installed by Jetstream. The action receives the currently authenticated user as well as an array containing all of the incoming request's input.

#### Profile Photos

Profile photo support for users is made available via the `Laravel\Jetstream\HasProfilePhoto` trait that is added to your application's `User` model during Jetstream's installation. This trait contains methods such as `updateProfilePhoto`, `getProfilePhotoUrlAttribute`, `defaultProfilePhotoUrl`, and `profilePhotoDisk` that may be overwritten by your own model class if you need to customize their behavior.

## Contributing

Thank you for considering contributing to Jetstream! You can read the contribution guide [here](.github/CONTRIBUTING.md).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

Please review [our security policy](https://github.com/laravel/jetstream/security/policy) on how to report security vulnerabilities.

## License

Laravel Jetstream is open-sourced software licensed under the [MIT license](LICENSE.md).

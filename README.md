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
    - [What Is Installed](#what-is-installed)
    - [Tailwind](#tailwind)
- [Basic Jetstream Features](#jetstream-features)
    - [Profile Management](#profile-management)
    - [Security Features](#security-features)
    - [Email Verification](#email-verification)
    - [Account Deletion](#account-deletion)
- [API Support](#api-support)
    - [Defining Permissions](#defining-permissions)
    - [Authorizing Incoming Requests](#authorizing-incoming-requests)
- [Teams](#teams)
    - [Member Management](#member-management)
    - [Roles / Permissions](#team-roles-and-permissions)
    - [Authorization](#team-authorization)
- [Livewire Features](#livewire-features)
    - [Components](#livewire-components)
    - [Modals](#livewire-modals)
- [Inertia Features](#inertia-features)
    - [Components](#inertia-components)
    - [Form / Validation Helpers](#inertia-form-validation-helpers)
    - [Modals](#inertia-modals)

<a name="introduction"></a>
## Introduction

Laravel Jetstream is a beautifully designed application scaffolding for Laravel. Jetstream provides the perfect starting point for your next Laravel application and includes login, registration, email verification, two-factor authentication, session management, API support via [Laravel Sanctum](https://github.com/laravel/sanctum), and optional team management.

Jetstream is designed using [Tailwind CSS](https://tailwindcss.com) and offers your choice of [Livewire](https://laravel-livewire.com) or [Inertia](https://inertiajs.com) scaffolding.

<p align="center"><img src="https://laravel.com/img/jetstream-preview.png"></p>

<a name="installation"></a>
## Installation

You may use Composer to install Jetstream into your new Laravel project:

    composer require laravel/jetstream

After installing Jetstream, you should run the `jetstream:install` Artisan command. This command accepts the name of the stack you prefer (`livewire` or `inertia`). You are highly encouraged to read through the entire documentation of [Livewire](https://laravel-livewire.com) or [Inertia](https://inertiajs.com) before beginning your Jetstream project. In addition, you may use the `teams` switch to enable team support:

    php artisan jetstream:install livewire

    php artisan jetstream:install inertia --teams

After installing your Jetstream stack, you should compile your frontend assets:

    npm install && npm run dev

<a name="what-is-installed"></a>
### What Is Installed

During installation, Jetstream will publish a variety of views and classes to your application. When using Livewire, views will be published to your `resources/views` directory. When using Inertia, "Pages" will be published to your `resources/js/Pages` directory. These views / pages contain every feature supported by Jetstream and you are free to customize them as needed. Think of Jetstream as a starting point for your application. Once you have installed Jetstream, you are free to customize anything you like.

In addition, "action" classes are published to your application's `app/Actions` directory. These action classes typically perform a single action and correspond to a single Jetstream feature, such as creating a team or deleting a user. You are free to customize these classes if you would like to tweak the backend behavior of Jetstream.

#### Dashboard

The "main" view of your application is published at `resources/views/dashboard.blade.php` when using Livewire and `resources/js/Pages/Dashboard.vue` when using Inertia. You are free to use this as a starting point for building primary view of your application.

#### Livewire Components

Jetstream uses a variety of Blade components, such as buttons and modals, to power the Livewire stack. If you would like to publish these components, you may use the `vendor:publish` Artisan command:

    php artisan vendor:publish --tag=jetstream-views

#### Using The Laravel Installer

If the [Laravel installer](https://github.com/laravel/installer) is installed on your machine, you may use the `jet` switch to create a new Jetstream powered Laravel application:

    laravel new project-name --jet

<a name="tailwind"></a>
### Tailwind

During installation, Jetstream will scaffold your application's integration with the Tailwind CSS framework. Specifically, a `webpack.mix.js` file and `tailwind.config.js` file will be created. These two files are used to build your compiled application CSS output. You are free to modify these files as needed for your application.

In addition, your `tailwind.config.js` file has been preconfigured to support PurgeCSS with the relevant directories properly specified depending on your chosen Jetstream stack.

<a name="jetstream-features"></a>
## Basic Jetstream Features

Various Jetstream features may be enabled / disabled via the `features` option within your application's `jetstream` configuration file.

<a name="profile-management"></a>
### Profile Management

Jetstream includes forms that allow the user to update their name and email address, as well as upload a profile photo. The backend logic for this feature may be customized by modifying the `App\Actions\Fortify\UpdateUserProfileInformation` class installed by Jetstream. The action receives the currently authenticated user as well as an array containing all of the incoming request's input.

#### Profile Photos

Profile photo support for users is made available via the `Laravel\Jetstream\HasProfilePhoto` trait that is added to your application's `User` model during Jetstream's installation. This trait contains methods such as `updateProfilePhoto`, `getProfilePhotoUrlAttribute`, `defaultProfilePhotoUrl`, and `profilePhotoDisk` that may be overwritten by your own model class if you need to customize their behavior.

<a name="security-features"></a>
### Security Features

Jetstream includes forms for the user to update their password, enable two-factor authentication, and logout other authenticated browser sessions for their account. Typically, these features will not need customization. However, password reset / update and password validation logic may be customized by modifying the relevant action classes within your application's `app/Actions/Fortify` directory.

<a name="email-verification"></a>
### Email Verification

Jetstream includes support for email verification. After a user registers for your application, they will be redirected to a screen instructing them to click the email verification link that has been sent to their registered email address.

To enable email verification, you should uncomment the `emailVerification` feature in the `features` option of your `fortify` configuration file. In addition, you should ensure your `User` model implements the `MustVerifyEmail` interface.

<a name="account-deletion"></a>
### Account Deletion

Jetstream includes an action panel that allows the user to completely delete their account. The backend logic for this action may be customized via the `App\Actions\Jetstream\DeleteUser` action class.

<a name="api-support"></a>
## API Support

Jetstream includes first-party integration with [Laravel Sanctum](https://laravel.com/docs/sanctum). Laravel Sanctum provides a featherweight authentication system for SPAs (single page applications), mobile applications, and simple, token based APIs. Sanctum allows each user of your application to generate multiple API tokens for their account. These tokens may be granted abilities / permissions which specify which actions the tokens are allowed to perform.

For more information on Sanctum and to learn how to issue requests to a Sanctum authenticated API, please consult the official [Sanctum documentation](https://laravel.com/docs/sanctum).

By default, the API token creation panel may be accessed using the "API" link of the top-right user profile dropdown menu. From this screen, users may create Sanctum API tokens that have various permissions.

<a name="defining-permissions"></a>
### Defining Permissions

The permissions available to API tokens are defined using the `Jetstream::permissions` method within your application's `JetstreamServiceProvider`:

```php
Jetstream::defaultApiTokenPermissions(['read']);

Jetstream::permissions([
    'create',
    'read',
    'update',
    'delete',
]);
```

The `defaultApiTokenPermissions` method may be used to specify which permissions should be selected by default when creating a new API token. Of course, a user may uncheck a default permission before creating the token.

<a name="authorizing-incoming-requests"></a>
### Authorizing Incoming Requests

Every request made to your Jetstream application, even to authenticated routes within your `routes/web.php` file, will be associated with a Sanctum token object. You may determine if the associated token has a given permission using the `tokenCan` method provided by the `Laravel\Jetstream\HasApiTokens` trait. This trait is automatically applied to your application's `User` model:

```php
$request->user()->tokenCan('read');
```

When a user makes a request to a route within your `routes/web.php` file, the request will typically be authenticated by Sanctum through a cookie based `web` guard. Since the user is making a first-party request through the application UI in this case, the `tokenCan` method will always return `true`.

<a name="teams"></a>
## Teams

If you installed Jetstream using the `teams` option, your application will be scaffolded to support team creation and management. By default, every registered user will belong to a `Personal` team. After registration, the user may rename this team or create additional teams.

Team creation and deletion logic may be customized by modifying the relevant action classes within your `app/Actions/Jetstream` directory. These actions include `CreateTeam`, `UpdateTeamName`, and `DeleteTeam`.

Information about a user's teams may be accessed via the methods provided by the `Laravel\Jetstream\HasTeams` trait. This trait is automatically applied to your application's `User` model:

```php
// Access a user's currently selected team...
$user->currentTeam : Laravel\Jetstream\Team

// Access all of the team's (including owned teams) that a user belongs to...
$user->allTeams() : Illuminate\Support\Collection

// Access all of a user's owned teams...
$user->ownedTeams : Illuminate\Database\Eloquent\Collection

// Access all of the teams that a user belongs to but does not own...
$user->teams : Illuminate\Database\Eloquent\Collection

// Access a user's "personal" team...
$user->personalTeam() : Laravel\Jetstream\Team

// Determine if a user owns a given team...
$user->ownsTeam($team) : bool

// Determine if a user belongs to a given team...
$user->belongsToTeam($team) : bool

// Access an array of all permissions a user has for a given team...
$user->teamPermissions($team) : array

// Determine if a user has a given team permission...
$user->hasTeamPermission($team, 'server:create') : bool
```

### Current Team

Every user within Jetstream has a "current team". This is the team that the user is actively viewing resources for. For example, if you are building a calendar application, your application would display the upcoming calendar events for the user's current team.

You may access the user's current team using the `$user->currentTeam` Eloquent relationship. This team may be used to scope your other Eloquent queries by the team.

A user may switch their current team via the user profile dropdown menu available within the Jetstream navigation bar.

<a name="member-management"></a>
### Member Management

Team members may be added and removed via Jetstream's "Team Settings" view. The backend logic that manages these actions may be customized by modifying the relevant actions, such as `AddTeamMember`, within the `app/Actions/Jetstream` directory.

<a name="team-roles-and-permissions"></a>
### Roles / Permissions

Each team member added to a team is assigned a given role, and each role is assigned a set of permissions. Role permissions are defined in your application's `JetstreamServiceProvider`:

```php
Jetstream::defaultApiTokenPermissions(['read']);

Jetstream::role('admin', 'Administrator', [
    'create',
    'read',
    'update',
    'delete',
])->description('Administrator users can perform any action.');

Jetstream::role('editor', 'Editor', [
    'read',
    'create',
    'update',
])->description('Editor users have the ability to read, create, and update.');
```

> **Note:** When Jetstream is installed with team support, available API permissions are automatically derived by combining all unique permissions available to roles.

<a name="team-authorization"></a>
### Authorization

A user's team permissions may be determined using the `hasTeamPermission` method available via the `Laravel\Jetstream\HasTeams` trait:

```php
if ($request->user()->hasTeamPermission($team, 'read')) {
    //
}
```

#### Combining Team Permissions With API Permissions

When building a Jetstream application that utilizes API support and team support, you should verify an incoming request's team permissions and API token permissions within your authorization policies. This important because an API token may have the theoretical ability to perform an action while a user does not actually have that action granted to them via their team permissions:

```php
/**
 * Determine whether the user can view a flight.
 *
 * @param  \App\Models\User  $user
 * @param  \App\Models\Flight  $flight
 * @return bool
 */
public function view(User $user, Flight $flight)
{
    return $user->belongsToTeam($flight->team) &&
           $user->hasTeamPermission($flight->team, 'flight:view') &&
           $user->tokenCan('flight:view');
}
```

<a name="livewire-features"></a>
## Livewire Features

When using the Livewire stack, Jetstream has some unique features that you should be aware of. We will discuss each of these features below.

<a name="livewire-components"></a>
### Components

While building the Jetstream Livewire stack, a variety of Blade components (buttons, panels, inputs, modals) were created to assist in creating UI consistency and ease of use. You are free to use or not use these components. However, if you would like to use them, you may publish them using the Artisan `vendor:publish` command:

    php artisan vendor:publish --tag=jetstream-views

You may gain insight into how to use these components by reviewing their usage within Jetstream's existing views located within your `resources/views` directory.

<a name="livewire-modals"></a>
### Modals

Most of the the Jetstream Livewire stack's components have no communication with your backend. However, the Livewire modal components included with Jetstream do interact with your Livewire backend to determine their open / closed state. In addition, Jetstream includes two types of modals: `dialog-modal` and `confirmation-modal`. The `confirmation-modal` may be used when confirming destructive actions such as deletions, while the `dialog-modal` is a more generic modal window that may be used at any time.

To illustrate the use of modals, consider the following modal that confirms a user would like to delete their account:

```html
<x-jet-confirmation-modal wire:model="confirmingUserDeletion">
    <x-slot name="title">
        Delete Account
    </x-slot>

    <x-slot name="content">
        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.
    </x-slot>

    <x-slot name="footer">
        <x-jet-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
            Nevermind
        </x-jet-secondary-button>

        <x-jet-danger-button class="ml-2" wire:click="deleteUser" wire:loading.attr="disabled">
            Delete Account
        </x-jet-danger-button>
    </x-slot>
</x-jet-confirmation-modal>
```

As you can see, the modal's open / close state is determined by a `wire:model` property that is declared on the component. This property name should correspond to a boolean property on your Livewire component's corresponding PHP class. The modal's contents may be specified by hydrating three slots: `title`, `content`, and `footer`.

<a name="inertia-features"></a>
## Inertia Features

When using the Inertia stack, Jetstream has some unique features that you should be aware of. We will discuss each of these features below.

<a name="inertia-components"></a>
### Components

While building the Jetstream Inertia stack, a variety of Vue components (buttons, panels, inputs, modals) were created to assist in creating UI consistency and ease of use. You are free to use or not use these components. All of these components are located within your application's `resources/js/Jetstream` directory.

You may gain insight into how to use these components by reviewing their usage within Jetstream's existing pages located within your `resources/js/Pages` directory.

<a name="inertia-form-validation-helpers"></a>
### Form / Validation Helpers

In order to make working with forms and validation errors more convenient, we have created a [laravel-jetstream](https://github.com/laravel/jetstream-js) NPM package. This package is automatically installed when using the Jetstream Inertia stack.

This package adds a new `form` method to the `$inertia` object that may be accessed via your Vue components. The `form` method is used to create a new form object that will provide convenient access to error messages, as well as conveniences such as resetting the form state on a successful form submission:

```js
data() {
    return {
        form: this.$inertia.form({
            name: this.name,
            email: this.email,
        }, {
            bag: 'updateProfileInformation',
            resetOnSuccess: true,
        }),
    }
}
```

A form may be submitted using the `post`, `put`, or `delete` methods. All of the data specified during the form's creation will be automatically included in the request. In addition, Inertia request options may also be specified:

```js
this.form.post('/user/profile-information', {
    preserveScroll: true
})
```

Form error messages may be access using the `form.error` method:

```html
<jet-input-error :message="form.error('email')" class="mt-2" />
```

A flattened list of all validation errors may be accessed using the `errors` method:

```html
<li v-for="error in form.errors()">
    {{ error }}
</li>
```

Additional information about the form's current state is available via the `recentlySuccessful` and `processing` methods:

```html
<jet-action-message :on="form.recentlySuccessful" class="mr-3">
    Saved.
</jet-action-message>

<jet-button :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
    Save
</jet-button>
```

To learn more about using Jetstream's Inertia form helpers, you are free to review the Inertia pages created during Jetstream's installation. These pages are located within your application's `resources/js/Pages` directory.

<a name="inertia-modals"></a>
### Modals

Jetstream's Inertia stack also includes two modal components: `DialogModal` and `ConfirmationModal`. The `ConfirmationModal` may be used when confirming destructive actions such as deletions, while the `DialogModal` is a more generic modal window that may be used at any time.

To illustrate the use of modals, consider the following modal that confirms a user would like to delete their account:

```html
<jet-confirmation-modal :show="confirmingUserDeletion" @close="confirmingUserDeletion = false">
    <template #title>
        Delete Account
    </template>

    <template #content>
        Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.
    </template>

    <template #footer>
        <jet-secondary-button @click.native="confirmingUserDeletion = false">
            Nevermind
        </jet-secondary-button>

        <jet-danger-button class="ml-2" @click.native="deleteTeam" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
            Delete Account
        </jet-danger-button>
    </template>
</jet-confirmation-modal>
```

As you can see, the modal's open / close state is determined by a `show` property that is declared on the component. The modal's contents may be specified by hydrating three slots: `title`, `content`, and `footer`.

## Contributing

Thank you for considering contributing to Jetstream! You can read the contribution guide [here](.github/CONTRIBUTING.md).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

Please review [our security policy](https://github.com/laravel/jetstream/security/policy) on how to report security vulnerabilities.

## License

Laravel Jetstream is open-sourced software licensed under the [MIT license](LICENSE.md).

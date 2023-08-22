# Upgrade Guide

## Upgrading from Jetstream 3.x to Jetstream 4.x

> **Note**
> This upgrade guide only discusses upgrading to Jetstream 4.x. Upgrading your Laravel, Tailwind, Livewire, or Inertia installations is outside the scope of this documentation and is not strictly required in order to use Jetstream 4.x. Please consult the upgrade guides for those libraries for information on their upgrade process.

- [Changes Common To Both Stacks](#jetstream-4x-changes-common-to-both-stacks)
- [Livewire Stack Upgrade Guide](#jetstream-4x-livewire-stack)

### Jetstream 4.x Changes Common To Both Stacks

#### Dependency Versions

Next, you should upgrade your `laravel/jetstream` dependency to `^4.0` within your application's `composer.json` file and run the `composer update` command:

    composer update

### Jetstream 4.x Livewire Stack

This upgrade guide assumes you have already upgraded your application to Livewire 3.x, and ran the `php artisan livewire:upgrade` command against the views published by Jetstream.

#### Alpine Script

As you may know, Livewire 3 ships with AlpineJS by default, and it's recommended to not include it your `resources/js/app.js` file.

As such, you should include `@livewireStyles` and `@livewireScripts` in your `resources/views/layouts/guest.blade.php` file as AlpineJS is used by "guest" components published by Jetstream:

```diff
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js']) 

```diff
        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
+
+       <!-- Styles -->
+       @livewireStyles
    </head>
    <body>
        <div class="font-sans text-gray-900 dark:text-gray-100 antialiased">
            {{ $slot }}
        </div>
+
+       @livewireScripts
    </body>
```

## Upgrading from Jetstream 2.x to Jetstream 3.x

> **Note**
> This upgrade guide only discusses upgrading to Jetstream 3.x. Upgrading your Laravel, Tailwind, Livewire, or Inertia installations is outside the scope of this documentation and is not strictly required in order to use Jetstream 3.x. Please consult the upgrade guides for those libraries for information on their upgrade process.

- [Changes Common To Both Stacks](#jetstream-3x-changes-common-to-both-stacks)
- [Livewire Stack Upgrade Guide](#jetstream-3x-livewire-stack)
- [Inertia Stack Upgrade Guide](#jetstream-3x-inertia-stack)

### Jetstream 3.x Changes Common To Both Stacks

#### Publish Views

**Before upgrading**, you should publish all of Jetstream's views using the `vendor:publish` Artisan command. You may skip this step if you have already published Jetstream's views:

    php artisan vendor:publish --tag=jetstream-views

#### Dependency Versions

Next, you should upgrade your `laravel/jetstream` dependency to `^3.0` within your application's `composer.json` file and run the `composer update` command:

    composer update

### Jetstream 3.x Livewire Stack

#### Views

You should move the published Jetstream components from `resources/views/vendor/jetstream/components` to `resources/views/components`.

You should also move the published Jetstream mail views from `resources/views/vendor/jetstream/mail` to `resources/views/emails`, taking care to note the new directory name of `emails` instead of `mail`.

Next, you should remove all references to the `jet-` prefix from your views. For example:

```diff
- <x-jet-banner />
+ <x-banner />

- <x-jet-switchable-team :team="$team" component="jet-responsive-nav-link" />
+ <x-switchable-team :team="$team" component="responsive-nav-link" />

- @props(['team', 'component' => 'jet-dropdown-link'])
+ @props(['team', 'component' => 'dropdown-link'])
```

Finally, clear your view cache:

    php artisan view:clear

### Jetstream 3.x Inertia Stack

#### Mail Views

You should move the published Jetstream mail views from `resources/views/vendor/jetstream/mail` to `resources/views/emails`, taking care to note the new directory name of `emails` instead of `mail`.

Next, clear your view cache:

    php artisan view:clear

#### Accessing The Authenticated User

You should change all references of `$page.props.user` to `$page.props.auth.user` and `usePage().props.user` to `usePage().props.auth.user`.

If you are using an Inertia version prior to 1.0, you will need to replace `usePage().props.value.user` with `usePage().props.value.auth.user`.

For example:

```diff
- <DropdownLink :href="route('teams.show', $page.props.user.current_team)">
+ <DropdownLink :href="route('teams.show', $page.props.auth.user.current_team)">

- leaveTeamForm.delete(route('team-members.destroy', [props.team, usePage().props.user]));
+ leaveTeamForm.delete(route('team-members.destroy', [props.team, usePage().props.auth.user]));

- leaveTeamForm.delete(route('team-members.destroy', [props.team, usePage().props.value.user]));
+ leaveTeamForm.delete(route('team-members.destroy', [props.team, usePage().props.value.auth.user]));
```

## Upgrading From Jetstream 1.x To Jetstream 2.x

> **Note**
> This upgrade guide only discusses upgrading to Jetstream 2.x. Upgrading your Tailwind, Livewire or Inertia installations is outside the scope of this documentation and is not strictly required in order to use Jetstream 2.x. Please consult the upgrade guides for those libraries for information on their upgrade process.

- [Changes Common To Both Stacks](#changes-common-to-both-stacks)
- [Livewire Stack Upgrade Guide](#livewire-stack)
- [Inertia Stack Upgrade Guide](#inertia-stack)

### Changes Common To Both Stacks

#### Publish Views

Before upgrading, you should publish all of Jetstream's views using the `vendor:publish` Artisan command. You may skip this step if you have already published Jetstream's views:

    php artisan vendor:publish --tag=jetstream-views

#### Dependency Versions

Next, you should upgrade your `laravel/jetstream` dependency to `^2.0` within your application's `composer.json` file and run the `composer update` command:

    composer update

#### New Jetstream Actions

You should place the new [RemoveTeamMember](https://github.com/laravel/jetstream/blob/2.x/stubs/app/Actions/Jetstream/RemoveTeamMember.php) and [InviteTeamMember](https://github.com/laravel/jetstream/blob/2.x/stubs/app/Actions/Jetstream/InviteTeamMember.php) actions within your application's `app/Actions/Jetstream` directory.

In addition, you should register these actions with Jetstream by adding the following code to the `boot` method of your application's `JetstreamServiceProvider`:

```php
use App\Actions\Jetstream\InviteTeamMember;
use App\Actions\Jetstream\RemoveTeamMember;

Jetstream::inviteTeamMembersUsing(InviteTeamMember::class);
Jetstream::removeTeamMembersUsing(RemoveTeamMember::class);
```

#### Team Invitation Model

You should place the new [TeamInvitation](https://github.com/laravel/jetstream/blob/2.x/stubs/app/Models/TeamInvitation.php) model within your application's `app/Models` directory.

In addition, you should create a `team_invitations` database migration:

    php artisan make:migration create_team_invitations_table

The generated migration should have the following content:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTeamInvitationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained()->cascadeOnDelete();
            $table->string('email')->unique();
            $table->string('role')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('team_invitations');
    }
}
```

### Livewire Stack

#### Navigation Menu

Rename the `resources/views/navigation-dropdown.blade.php` file to `navigation-menu.blade.php`. In addition, ensure that you have updated the reference to this view in your application's `app.blade.php` layout.

### Inertia Stack

#### Authentication Views

Jetstream 2.0's Inertia stack uses Vue based authentication pages. In order to use the new Vue based authentication pages, you will need to publish them using the `vendor:publish` Artisan command:

    php artisan vendor:publish --tag=jetstream-inertia-auth-pages

Or, if you wish to to continue to render your Blade based authentication views in Jetstream 2.x, you should add the following code to the `boot` method of your application's `JetstreamServiceProvider` class:

```php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Fortify;

Fortify::loginView(function () {
    return view('auth/login', [
        'canResetPassword' => Route::has('password.request'),
        'status' => session('status'),
    ]);
});

Fortify::requestPasswordResetLinkView(function () {
    return view('auth/forgot-password', [
        'status' => session('status'),
    ]);
});

Fortify::resetPasswordView(function (Request $request) {
    return view('auth/reset-password', [
        'email' => $request->input('email'),
        'token' => $request->route('token'),
    ]);
});

Fortify::registerView(function () {
    return view('auth/register');
});

Fortify::verifyEmailView(function () {
    return view('auth/verify-email', [
        'status' => session('status'),
    ]);
});

Fortify::twoFactorChallengeView(function () {
    return view('auth/two-factor-challenge');
});

Fortify::confirmPasswordView(function () {
    return view('auth/confirm-password');
});
```

#### Remove [laravel-jetstream](https://www.npmjs.com/package/laravel-jetstream) NPM Package

As of the Jetstream 2.0 release, this library is no longer necessary as all of its features have been incorporated into Inertia itself. You should remove the following from your `resources/js/app.js` file:

```
import {InertiaForm} from 'laravel-jetstream';

Vue.use(InertiaForm);

````

Finally, you may remove the package:

`npm uninstall laravel-jetstream`

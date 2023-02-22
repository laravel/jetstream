<?php

namespace Laravel\Jetstream;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Contracts\DeletesUsers;
use Laravel\Jetstream\Contracts\InvitesTeamMembers;
use Laravel\Jetstream\Contracts\RemovesTeamMembers;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;

class Jetstream
{
    /**
     * Indicates if Jetstream routes will be registered.
     */
    public static bool $registersRoutes = true;

    /**
     * The roles that are available to assign to users.
     */
    public static array $roles = [];

    /**
     * The permissions that exist within the application.
     */
    public static array $permissions = [];

    /**
     * The default permissions that should be available to new entities.
     */
    public static array $defaultPermissions = [];

    /**
     * The user model that should be used by Jetstream.
     */
    public static string $userModel = 'App\\Models\\User';

    /**
     * The team model that should be used by Jetstream.
     */
    public static string $teamModel = 'App\\Models\\Team';

    /**
     * The membership model that should be used by Jetstream.
     */
    public static string $membershipModel = 'App\\Models\\Membership';

    /**
     * The team invitation model that should be used by Jetstream.
     */
    public static string $teamInvitationModel = 'App\\Models\\TeamInvitation';

    /**
     * The Inertia manager instance.
     */
    public static ?InertiaManager $inertiaManager = null;

    /**
     * Determine if Jetstream has registered roles.
     */
    public static function hasRoles(): bool
    {
        return count(static::$roles) > 0;
    }

    /**
     * Find the role with the given key.
     */
    public static function findRole(string $key): Role|null
    {
        return static::$roles[$key] ?? null;
    }

    /**
     * Define a role.
     */
    public static function role(string $key, string $name, array $permissions): Role
    {
        static::$permissions = collect(array_merge(static::$permissions, $permissions))
                                    ->unique()
                                    ->sort()
                                    ->values()
                                    ->all();

        return tap(new Role($key, $name, $permissions), function ($role) use ($key) {
            static::$roles[$key] = $role;
        });
    }

    /**
     * Determine if any permissions have been registered with Jetstream.
     */
    public static function hasPermissions(): bool
    {
        return count(static::$permissions) > 0;
    }

    /**
     * Define the available API token permissions.
     */
    public static function permissions(array $permissions): static
    {
        static::$permissions = $permissions;

        return new static;
    }

    /**
     * Define the default permissions that should be available to new API tokens.
     */
    public static function defaultApiTokenPermissions(array $permissions): static
    {
        static::$defaultPermissions = $permissions;

        return new static;
    }

    /**
     * Return the permissions in the given list that are actually defined permissions for the application.
     */
    public static function validPermissions(array $permissions): array
    {
        return array_values(array_intersect($permissions, static::$permissions));
    }

    /**
     * Determine if Jetstream is managing profile photos.
     */
    public static function managesProfilePhotos(): bool
    {
        return Features::managesProfilePhotos();
    }

    /**
     * Determine if Jetstream is supporting API features.
     */
    public static function hasApiFeatures(): bool
    {
        return Features::hasApiFeatures();
    }

    /**
     * Determine if Jetstream is supporting team features.
     */
    public static function hasTeamFeatures(): bool
    {
        return Features::hasTeamFeatures();
    }

    /**
     * Determine if a given user model utilizes the "HasTeams" trait.
     */
    public static function userHasTeamFeatures(Model $user): bool
    {
        return (array_key_exists(HasTeams::class, class_uses_recursive($user)) ||
                method_exists($user, 'currentTeam')) &&
                static::hasTeamFeatures();
    }

    /**
     * Determine if the application is using the terms confirmation feature.
     */
    public static function hasTermsAndPrivacyPolicyFeature(): bool
    {
        return Features::hasTermsAndPrivacyPolicyFeature();
    }

    /**
     * Determine if the application is using any account deletion features.
     */
    public static function hasAccountDeletionFeatures(): bool
    {
        return Features::hasAccountDeletionFeatures();
    }

    /**
     * Find a user instance by the given ID.
     */
    public static function findUserByIdOrFail(int $id): mixed
    {
        return static::newUserModel()->where('id', $id)->firstOrFail();
    }

    /**
     * Find a user instance by the given email address or fail.
     */
    public static function findUserByEmailOrFail(string $email): mixed
    {
        return static::newUserModel()->where('email', $email)->firstOrFail();
    }

    /**
     * Get the name of the user model used by the application.
     */
    public static function userModel(): string
    {
        return static::$userModel;
    }

    /**
     * Get a new instance of the user model.
     */
    public static function newUserModel(): mixed
    {
        $model = static::userModel();

        return new $model;
    }

    /**
     * Specify the user model that should be used by Jetstream.
     */
    public static function useUserModel(string $model): static
    {
        static::$userModel = $model;

        return new static;
    }

    /**
     * Get the name of the team model used by the application.
     */
    public static function teamModel(): string
    {
        return static::$teamModel;
    }

    /**
     * Get a new instance of the team model.
     */
    public static function newTeamModel(): mixed
    {
        $model = static::teamModel();

        return new $model;
    }

    /**
     * Specify the team model that should be used by Jetstream.
     */
    public static function useTeamModel(string $model): static
    {
        static::$teamModel = $model;

        return new static;
    }

    /**
     * Get the name of the membership model used by the application.
     */
    public static function membershipModel(): string
    {
        return static::$membershipModel;
    }

    /**
     * Specify the membership model that should be used by Jetstream.
     */
    public static function useMembershipModel(string $model): static
    {
        static::$membershipModel = $model;

        return new static;
    }

    /**
     * Get the name of the team invitation model used by the application.
     */
    public static function teamInvitationModel(): string
    {
        return static::$teamInvitationModel;
    }

    /**
     * Specify the team invitation model that should be used by Jetstream.
     */
    public static function useTeamInvitationModel(string $model): static
    {
        static::$teamInvitationModel = $model;

        return new static;
    }

    /**
     * Register a class / callback that should be used to create teams.
     */
    public static function createTeamsUsing(string $class): void
    {
        app()->singleton(CreatesTeams::class, $class);
    }

    /**
     * Register a class / callback that should be used to update team names.
     */
    public static function updateTeamNamesUsing(string $class): void
    {
        app()->singleton(UpdatesTeamNames::class, $class);
    }

    /**
     * Register a class / callback that should be used to add team members.
     */
    public static function addTeamMembersUsing(string $class): void
    {
        app()->singleton(AddsTeamMembers::class, $class);
    }

    /**
     * Register a class / callback that should be used to add team members.
     */
    public static function inviteTeamMembersUsing(string $class): void
    {
        app()->singleton(InvitesTeamMembers::class, $class);
    }

    /**
     * Register a class / callback that should be used to remove team members.
     */
    public static function removeTeamMembersUsing(string $class): void
    {
        app()->singleton(RemovesTeamMembers::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete teams.
     */
    public static function deleteTeamsUsing(string $class): void
    {
        app()->singleton(DeletesTeams::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete users.
     */
    public static function deleteUsersUsing(string $class): void
    {
        app()->singleton(DeletesUsers::class, $class);
    }

    /**
     * Manage Jetstream's Inertia settings.
     */
    public static function inertia(): ?InertiaManager
    {
        if (is_null(static::$inertiaManager)) {
            static::$inertiaManager = new InertiaManager;
        }

        return static::$inertiaManager;
    }

    /**
     * Find the path to a localized Markdown resource.
     */
    public static function localizedMarkdownPath(string $name): string|null
    {
        $localName = preg_replace('#(\.md)$#i', '.'.app()->getLocale().'$1', $name);

        return Arr::first([
            resource_path('markdown/'.$localName),
            resource_path('markdown/'.$name),
        ], function ($path) {
            return file_exists($path);
        });
    }

    /**
     * Configure Jetstream to not register its routes.
     */
    public static function ignoreRoutes(): static
    {
        static::$registersRoutes = false;

        return new static;
    }
}

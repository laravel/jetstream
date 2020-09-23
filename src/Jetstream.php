<?php

namespace Laravel\Jetstream;

use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Support\Collection;
use Laravel\Jetstream\Contracts\AddsTeamMembers;
use Laravel\Jetstream\Contracts\CreatesTeams;
use Laravel\Jetstream\Contracts\DeletesTeams;
use Laravel\Jetstream\Contracts\DeletesUsers;
use Laravel\Jetstream\Contracts\UpdatesTeamNames;

class Jetstream
{
    /**
     * Indicates if Jetstream routes will be registered.
     *
     * @var bool
     */
    public static $registersRoutes = true;

    /**
     * The roles that are available to assign to users.
     *
     * @var array
     */
    public static $roles = [];

    /**
     * The permissions that exist within the application.
     *
     * @var array
     */
    public static $permissions = [];

    /**
     * The default permissions that should be available to new entities.
     *
     * @var array
     */
    public static $defaultPermissions = [];

    /**
     * The user model that should be used by Jetstream.
     *
     * @var string
     */
    public static $userModel = 'App\\Models\\User';

    /**
     * The team model that should be used by Jetstream.
     *
     * @var string
     */
    public static $teamModel = 'App\\Models\\Team';

    /**
     * The membership model that should be used by Jetstream.
     *
     * @var string
     */
    public static $membershipModel = 'App\\Models\\Membership';

    /**
     * The permission model that should be used by Jetstream.
     *
     * @var string
     */
    public static $permissionModel = null;

    /**
     * The role model that should be used by Jetstream.
     *
     * @var string
     */
    public static $teamRoleModel = null;

    /**
     * The key identifier field that should be used on the role model when supplied to Jetstream.
     *
     * @var string
     */
    public static $teamRoleKeyField = 'key';

    /**
     * The Inertia manager instance.
     *
     * @var \Laravel\Jetstream\InertiaManager
     */
    public static $inertiaManager;

    /**
     * Determine if Jetstream has registered roles.
     *
     * @return bool
     */
    public static function hasRoles()
    {
        if (static::hasTeamRolesModel()) {
            return static::newUserModel()->count() > 0;
        }

        return count(static::$roles) > 0;
    }

    /**
     * Determine if a model has been provided to Jetstream for roles.
     *
     * @return bool
     */
    public static function hasTeamRolesModel()
    {
        return static::$teamRoleModel !== null;
    }

    /**
     * Find the role with the given key.
     *
     * @param  string  $key
     * @return \Laravel\Jetstream\Role
     */
    public static function findRole(string $key)
    {
        if (static::hasTeamRolesModel()) {
            return static::newTeamRoleModel()->where(static::teamRoleKeyField(), $key)->first();
        }

        return static::$roles[$key] ?? null;
    }

    /**
     * Define a role.
     *
     * @param  string  $key
     * @param  string  $name
     * @param  array  $permissions
     * @return \Laravel\Jetstream\Role
     */
    public static function role(string $key, string $name, array $permissions)
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
     *
     * @return bool
     */
    public static function hasPermissions()
    {
        if (static::hasPermissionsModel()) {
            return static::newUserModel()->count() > 0;
        }

        return count(static::$permissions) > 0;
    }

    /**
     * Determine if a model has been provided to Jetstream for permissions.
     *
     * @return bool
     */
    public static function hasPermissionsModel()
    {
        return static::$permissionModel !== null;
    }

    /**
     * Define the available API token permissions.
     *
     * @param  array  $permissions
     * @return static
     */
    public static function permissions(array $permissions)
    {
        static::$permissions = $permissions;

        return new static;
    }

    /**
     * Define the default permissions that should be available to new API tokens.
     *
     * @param  array  $permissions
     * @return static
     */
    public static function defaultApiTokenPermissions(array $permissions)
    {
        static::$defaultPermissions = $permissions;

        return new static;
    }

    /**
     * Return the permissions in the given list that are actually defined permissions for the application.
     *
     * @param  array  $permissions
     * @return array
     */
    public static function validPermissions(array $permissions)
    {
        return array_values(array_intersect($permissions, static::$permissions));
    }

    /**
     * Determine if Jetstream is managing profile photos.
     *
     * @return bool
     */
    public static function managesProfilePhotos()
    {
        return Features::managesProfilePhotos();
    }

    /**
     * Determine if Jetstream is supporting API features.
     *
     * @return bool
     */
    public static function hasApiFeatures()
    {
        return Features::hasApiFeatures();
    }

    /**
     * Determine if Jetstream is supporting team features.
     *
     * @return bool
     */
    public static function hasTeamFeatures()
    {
        return Features::hasTeamFeatures();
    }

    /**
     * Find a user instance by the given ID.
     *
     * @param  int  $id
     */
    public static function findUserByIdOrFail($id)
    {
        return static::newUserModel()->where('id', $id)->firstOrFail();
    }

    /**
     * Find a user instance by the given email address or fail.
     *
     * @param  string  $email
     * @return mixed
     */
    public static function findUserByEmailOrFail(string $email)
    {
        return static::newUserModel()->where('email', $email)->firstOrFail();
    }

    /**
     * Get the name of the user model used by the application.
     *
     * @return string
     */
    public static function userModel()
    {
        return static::$userModel;
    }

    /**
     * Get a new instance of the user model.
     *
     * @return mixed
     */
    public static function newUserModel()
    {
        $model = static::userModel();

        return new $model;
    }

    /**
     * Specify the user model that should be used by Jetstream.
     *
     * @param  string  $model
     * @return static
     */
    public static function useUserModel(string $model)
    {
        static::$userModel = $model;

        return new static;
    }

    /**
     * Get the name of the team model used by the application.
     *
     * @return string
     */
    public static function teamModel()
    {
        return static::$teamModel;
    }

    /**
     * Get a new instance of the team model.
     *
     * @return mixed
     */
    public static function newTeamModel()
    {
        $model = static::teamModel();

        return new $model;
    }

    /**
     * Specify the team model that should be used by Jetstream.
     *
     * @param  string  $model
     * @return static
     */
    public static function useTeamModel(string $model)
    {
        static::$teamModel = $model;

        return new static;
    }

    /**
     * Get the name of the team model used by the application.
     *
     * @return string
     */
    public static function membershipModel()
    {
        return static::$membershipModel;
    }

    /**
     * Specify the membership model that should be used by Jetstream.
     *
     * @param  string  $model
     * @return static
     */
    public static function useMembershipModel(string $model)
    {
        static::$membershipModel = $model;

        return new static;
    }

    /**
     * Get the name of the permission model used by the application.
     *
     * @return string
     */
    public static function permissionModel()
    {
        return static::$permissionModel;
    }

    /**
     * Get a new instance of the permission model.
     *
     * @return mixed
     */
    public static function newPermissionModel()
    {
        $model = static::permissionModel();

        return new $model;
    }

    /**
     * Specify the permission model that should be used by Jetstream.
     *
     * @param  string  $model
     * @return static
     */
    public static function usePermissionModel(string $model)
    {
        static::$permissionModel = $model;

        return new static;
    }

    /**
     * Get the name of the team role model used by the application.
     *
     * @return string
     */
    public static function teamRoleModel()
    {
        return static::$teamRoleModel;
    }

    /**
     * Get the key identifier field of the role model used by the application.
     *
     * @return string
     */
    public static function teamRoleKeyField()
    {
        return static::$teamRoleKeyField;
    }

    /**
     * Get a new instance of the team role model.
     *
     * @return mixed
     */
    public static function newTeamRoleModel()
    {
        $model = static::teamRoleModel();

        return new $model;
    }

    /**
     * Specify the team role model that should be used by Jetstream.
     *
     * @param  string  $model
     * @return static
     */
    public static function useTeamRoleModel(string $model)
    {
        static::$teamRoleModel = $model;

        if (! static::newTeamRoleModel()->permissions instanceof Collection) {
            throw new \Exception('TeamRole model supplied must have a permissions relationship.');
        }

//        @TODO - Check for existence of name field here
//        if (! static::newTeamRoleModel()->offsetExists('name')) {
//            throw new \Exception('TeamRole model supplied must have a name field');
//        }

        return new static;
    }

    /**
     * Register a class / callback that should be used to create teams.
     *
     * @param  string  $class
     * @return void
     */
    public static function createTeamsUsing(string $class)
    {
        return app()->singleton(CreatesTeams::class, $class);
    }

    /**
     * Register a class / callback that should be used to update team names.
     *
     * @param  string  $class
     * @return void
     */
    public static function updateTeamNamesUsing(string $class)
    {
        return app()->singleton(UpdatesTeamNames::class, $class);
    }

    /**
     * Register a class / callback that should be used to add team members.
     *
     * @param  string  $class
     * @return void
     */
    public static function addTeamMembersUsing(string $class)
    {
        return app()->singleton(AddsTeamMembers::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete teams.
     *
     * @param  string  $class
     * @return void
     */
    public static function deleteTeamsUsing(string $class)
    {
        return app()->singleton(DeletesTeams::class, $class);
    }

    /**
     * Register a class / callback that should be used to delete users.
     *
     * @param  string  $class
     * @return void
     */
    public static function deleteUsersUsing(string $class)
    {
        return app()->singleton(DeletesUsers::class, $class);
    }

    /**
     * Manage Jetstream's Inertia settings.
     *
     * @return \Laravel\Jetstream\InertiaManager
     */
    public static function inertia()
    {
        if (is_null(static::$inertiaManager)) {
            static::$inertiaManager = new InertiaManager;
        }

        return static::$inertiaManager;
    }

    /**
     * Configure Jetstream to not register its routes.
     *
     * @return static
     */
    public static function ignoreRoutes()
    {
        static::$registersRoutes = false;

        return new static;
    }
}

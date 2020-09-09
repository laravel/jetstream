<?php

namespace Laravel\Jetstream;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;

trait HasTeams
{
    /**
     * Determine if the given team is the current team.
     *
     * @param  mixed  $team
     * @return bool
     */
    public function isCurrentTeam($team)
    {
        return $team->id === $this->currentTeam->id;
    }

    /**
     * Get the current team of the user's context.
     */
    public function currentTeam()
    {
        if (is_null($this->current_team_id)) {
            $this->forceFill([
                'current_team_id' => $this->personalTeam()->id,
            ])->save();
        }

        return $this->belongsTo(Jetstream::teamModel(), 'current_team_id');
    }

    /**
     * Get all of the teams the user owns or belongs to.
     *
     * @return \Illuminate\Collections\Collection
     */
    public function allTeams()
    {
        return $this->ownedTeams->merge($this->teams)->sortBy('name');
    }

    /**
     * Get all of the teams the user belongs to.
     */
    public function ownedTeams()
    {
        return $this->hasMany(Jetstream::teamModel());
    }

    /**
     * Get all of the teams the user belongs to.
     */
    public function teams()
    {
        return $this->belongsToMany(Jetstream::teamModel())
                        ->withPivot('role')
                        ->withTimestamps()
                        ->using(Jetstream::membershipModel())
                        ->as('membership');
    }

    /**
     * Get the user's "personal" team.
     *
     * @return \App\Team
     */
    public function personalTeam()
    {
        return $this->ownedTeams->where('personal_team', true)->first();
    }

    /**
     * Determine if the user owns the given team.
     *
     * @param  mixed  $team
     * @return bool
     */
    public function ownsTeam($team)
    {
        return $this->id == $team->user_id;
    }

    /**
     * Determine if the user belongs to the given team.
     *
     * @param  mixed  $team
     * @return bool
     */
    public function belongsToTeam($team)
    {
        return $this->teams->contains(function ($t) use ($team) {
            return $t->id === $team->id;
        }) || $this->ownsTeam($team);
    }

    /**
     * Get the role that the user has on the team.
     *
     * @param  mixed  $team
     * @return \Laravel\Jetstream\Role
     */
    public function teamRole($team)
    {
        if ($this->ownsTeam($team)) {
            return new OwnerRole;
        }

        if (! $this->belongsToTeam($team)) {
            return;
        }

        return Jetstream::findRole($team->users->where(
            'id', $this->id
        )->first()->membership->role);
    }

    /**
     * Get the user's permissions for the given team.
     *
     * @param  mixed  $team
     * @return array
     */
    public function teamPermissions($team)
    {
        if ($this->ownsTeam($team)) {
            return ['*'];
        }

        if (! $this->belongsToTeam($team)) {
            return [];
        }

        return $this->teamRole($team)->permissions;
    }

    /**
     * Determine if the user has the given permission on the given team.
     *
     * @param  mixed  $team
     * @param  string  $permission
     * @return bool
     */
    public function hasTeamPermission($team, string $permission)
    {
        if ($this->ownsTeam($team)) {
            return true;
        }

        if (! $this->belongsToTeam($team)) {
            return false;
        }

        if (in_array(HasApiTokens::class, class_uses_recursive($this)) &&
            ! $this->tokenCan($permission) &&
            $this->currentAccessToken() !== null) {
            return false;
        }

        $permissions = $this->teamPermissions($team);

        return in_array($permission, $permissions) ||
               in_array('*', $permissions) ||
               (Str::endsWith($permission, ':create') && in_array('*:create', $permissions)) ||
               (Str::endsWith($permission, ':update') && in_array('*:update', $permissions));
    }
}

<?php

namespace Laravel\Jetstream;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;

abstract class Team extends Model
{
    /**
     * Get the owner of the team.
     *
     * @return BelongsTo
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(Jetstream::userModel(), 'user_id');
    }

    /**
     * Get all the team's users including its owner.
     *
     * @return Collection
     */
    public function allUsers(): Collection
    {
        return $this->users->merge([$this->owner]);
    }

    /**
     * Get all the users that belong to the team.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(Jetstream::userModel(), Jetstream::membershipModel())
                        ->withPivot('role')
                        ->withTimestamps()
                        ->as('membership');
    }

    /**
     * Determine if the given user belongs to the team.
     */
    public function hasUser(User $user): bool
    {
        return $this->users->contains($user) || $user->ownsTeam($this);
    }

    /**
     * Determine if the given email address belongs to a user on the team.
     */
    public function hasUserWithEmail(string $email): bool
    {
        return $this->allUsers()->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Determine if the given user has the given permission on the team.
     */
    public function userHasPermission(User $user, string $permission): bool
    {
        return $user->hasTeamPermission($this, $permission);
    }

    /**
     * Get all the pending user invitations for the team.
     */
    public function teamInvitations(): HasMany
    {
        return $this->hasMany(Jetstream::teamInvitationModel());
    }

    /**
     * Remove the given user from the team.
     */
    public function removeUser(User $user): void
    {
        if ($user->current_team_id === $this->id) {
            $user->forceFill([
                'current_team_id' => null,
            ])->save();
        }

        $this->users()->detach($user);
    }

    /**
     * Purge all the team's resources.
     */
    public function purge(): void
    {
        $this->owner()->where('current_team_id', $this->id)
                ->update(['current_team_id' => null]);

        $this->users()->where('current_team_id', $this->id)
                ->update(['current_team_id' => null]);

        $this->users()->detach();

        $this->delete();
    }
}

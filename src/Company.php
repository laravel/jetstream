<?php

namespace Laravel\Jetstream;

use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\Jetstream;

abstract class Company extends Model
{
    /**
     * Get the owner of the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo(Jetstream::userModel(), 'user_id');
    }

    /**
     * Get all of the company's users including its owner.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allUsers()
    {
        return $this->users->merge([$this->owner]);
    }

    /**
     * Get all of the users that belong to the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(Jetstream::userModel(), Jetstream::employeeshipModel())
                        ->withPivot('role')
                        ->withTimestamps()
                        ->as('employeeship');
    }

    /**
     * Determine if the given user belongs to the company.
     *
     * @param  \App\Models\User  $user
     * @return bool
     */
    public function hasUser($user)
    {
        return $this->users->contains($user) || $user->ownsCompany($this);
    }

    /**
     * Determine if the given email address belongs to a user on the company.
     *
     * @param  string  $email
     * @return bool
     */
    public function hasUserWithEmail(string $email)
    {
        return $this->allUsers()->contains(function ($user) use ($email) {
            return $user->email === $email;
        });
    }

    /**
     * Determine if the given user has the given permission on the company.
     *
     * @param  \App\Models\User  $user
     * @param  string  $permission
     * @return bool
     */
    public function userHasPermission($user, $permission)
    {
        return $user->hasCompanyPermission($this, $permission);
    }

    /**
     * Get all of the pending user invitations for the company.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function companyInvitations()
    {
        return $this->hasMany(Jetstream::companyInvitationModel());
    }

    /**
     * Remove the given user from the company.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    public function removeUser($user)
    {
        if ($user->current_company_id === $this->id) {
            $user->forceFill([
                'current_company_id' => null,
            ])->save();
        }

        $this->users()->detach($user);
    }

    /**
     * Purge all of the company's resources.
     *
     * @return void
     */
    public function purge()
    {
        $this->owner()->where('current_company_id', $this->id)
                ->update(['current_company_id' => null]);

        $this->users()->where('current_company_id', $this->id)
                ->update(['current_company_id' => null]);

        $this->users()->detach();

        $this->delete();
    }
}

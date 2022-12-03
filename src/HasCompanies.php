<?php

namespace Laravel\Jetstream;

use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\OwnerRole;

trait HasCompanies
{
    /**
     * Determine if the given company is the current company.
     *
     * @param  mixed  $company
     * @return bool
     */
    public function isCurrentCompany($company)
    {
        return $company->id === $this->currentCompany->id;
    }

    /**
     * Get the current company of the user's context.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currentCompany()
    {
        if (is_null($this->current_company_id) && $this->id) {
            $this->switchCompany($this->personalCompany());
        }

        return $this->belongsTo(Jetstream::companyModel(), 'current_company_id');
    }

    /**
     * Switch the user's context to the given company.
     *
     * @param  mixed  $company
     * @return bool
     */
    public function switchCompany($company)
    {
        if (! $this->belongsToCompany($company)) {
            return false;
        }

        $this->forceFill([
            'current_company_id' => $company->id,
        ])->save();

        $this->setRelation('currentCompany', $company);

        return true;
    }

    /**
     * Get all of the companies the user owns or belongs to.
     *
     * @return \Illuminate\Support\Collection
     */
    public function allCompanies()
    {
        return $this->ownedCompanies->merge($this->companies)->sortBy('name');
    }

    /**
     * Get all of the companies the user owns.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ownedCompanies()
    {
        return $this->hasMany(Jetstream::companyModel());
    }

    /**
     * Get all of the companies the user belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function companies()
    {
        return $this->belongsToMany(Jetstream::companyModel(), Jetstream::employeeshipModel())
                        ->withPivot('role')
                        ->withTimestamps()
                        ->as('employeeship');
    }

    /**
     * Get the user's "personal" company.
     *
     * @return \App\Models\Company
     */
    public function personalCompany()
    {
        return $this->ownedCompanies->where('personal_company', true)->first();
    }

    /**
     * Determine if the user owns the given company.
     *
     * @param  mixed  $company
     * @return bool
     */
    public function ownsCompany($company)
    {
        if (is_null($company)) {
            return false;
        }

        return $this->id == $company->{$this->getForeignKey()};
    }

    /**
     * Determine if the user belongs to the given company.
     *
     * @param  mixed  $company
     * @return bool
     */
    public function belongsToCompany($company)
    {
        if (is_null($company)) {
            return false;
        }

        return $this->ownsCompany($company) || $this->companies->contains(function ($t) use ($company) {
            return $t->id === $company->id;
        });
    }

    /**
     * Get the role that the user has on the company.
     *
     * @param  mixed  $company
     * @return \Laravel\Jetstream\Role|null
     */
    public function companyRole($company)
    {
        if ($this->ownsCompany($company)) {
            return new OwnerRole;
        }

        if (! $this->belongsToCompany($company)) {
            return;
        }

        $role = $company->users
            ->where('id', $this->id)
            ->first()
            ->employeeship
            ->role;

        return $role ? Jetstream::findRole($role) : null;
    }

    /**
     * Determine if the user has the given role on the given company.
     *
     * @param  mixed  $company
     * @param  string  $role
     * @return bool
     */
    public function hasCompanyRole($company, string $role)
    {
        if ($this->ownsCompany($company)) {
            return true;
        }

        return $this->belongsToCompany($company) && optional(Jetstream::findRole($company->users->where(
            'id', $this->id
        )->first()->employeeship->role))->key === $role;
    }

    /**
     * Get the user's permissions for the given company.
     *
     * @param  mixed  $company
     * @return array
     */
    public function companyPermissions($company)
    {
        if ($this->ownsCompany($company)) {
            return ['*'];
        }

        if (! $this->belongsToCompany($company)) {
            return [];
        }

        return (array) optional($this->companyRole($company))->permissions;
    }

    /**
     * Determine if the user has the given permission on the given company.
     *
     * @param  mixed  $company
     * @param  string  $permission
     * @return bool
     */
    public function hasCompanyPermission($company, string $permission)
    {
        if ($this->ownsCompany($company)) {
            return true;
        }

        if (! $this->belongsToCompany($company)) {
            return false;
        }

        if (in_array(HasApiTokens::class, class_uses_recursive($this)) &&
            ! $this->tokenCan($permission) &&
            $this->currentAccessToken() !== null) {
            return false;
        }

        $permissions = $this->companyPermissions($company);

        return in_array($permission, $permissions) ||
               in_array('*', $permissions) ||
               (Str::endsWith($permission, ':create') && in_array('*:create', $permissions)) ||
               (Str::endsWith($permission, ':update') && in_array('*:update', $permissions));
    }
}

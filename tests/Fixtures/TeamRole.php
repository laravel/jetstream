<?php

namespace Laravel\Jetstream\Tests\Fixtures;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TeamRole extends Model
{
    use HasFactory;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];

    public function permissions()
    {
        return $this->belongsToMany(Permission::class, 'team_role_permissions');
    }
}

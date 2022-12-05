<?php

namespace Laravel\Jetstream\Tests\Fixtures;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Jetstream\HasTeams;
use Laravel\Jetstream\HasCompanies;
use Laravel\Sanctum\HasApiTokens;

class Admin extends Authenticatable
{
    use HasApiTokens;
    use HasProfilePhoto;
    use HasTeams;
    use HasCompanies;

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array
     */
    protected $guarded = [];
}

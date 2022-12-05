<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\CompanyCreated;
use Laravel\Jetstream\Events\CompanyDeleted;
use Laravel\Jetstream\Events\CompanyUpdated;
use Laravel\Jetstream\Company as JetstreamCompany;

class Company extends JetstreamCompany
{
    use HasFactory;

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'personal_company' => 'boolean',
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'name',
        'personal_company',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => CompanyCreated::class,
        'updated' => CompanyUpdated::class,
        'deleted' => CompanyDeleted::class,
    ];
}

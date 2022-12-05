<?php

namespace App\Models;

use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\CompanyInvitation as JetstreamCompanyInvitation;

class CompanyInvitation extends JetstreamCompanyInvitation
{
    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'email',
        'role',
    ];

    /**
     * Get the company that the invitation belongs to.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function company()
    {
        return $this->belongsTo(Jetstream::companyModel());
    }
}

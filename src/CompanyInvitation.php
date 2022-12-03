<?php

namespace Laravel\Jetstream;

use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\Jetstream;

class CompanyInvitation extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
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

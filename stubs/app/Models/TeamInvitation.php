<?php

namespace App\Models;

use Laravel\Jetstream\TeamInvitation as JetstreamTeamInvitation;
use Laravel\Jetstream\Jetstream;

class TeamInvitation extends JetstreamTeamInvitation
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
     * Get the team that the invitation belongs to.
     */
    public function team()
    {
        return $this->belongsTo(Jetstream::teamModel());
    }
}

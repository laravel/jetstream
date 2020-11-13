<?php

namespace Laravel\Jetstream;

use Illuminate\Database\Eloquent\Model;

abstract class ConnectedAccount extends Model
{
    /**
     * Get user of the connected account.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Jetstream::userModel(), 'user_id');
    }
}

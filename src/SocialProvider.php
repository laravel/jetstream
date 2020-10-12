<?php

namespace Laravel\Jetstream;

use Illuminate\Database\Eloquent\Model;

abstract class SocialProvider extends Model
{
    /**
     * Get user of the social provider.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(Jetstream::userModel(), 'user_id');
    }
}

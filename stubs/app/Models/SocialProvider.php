<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Jetstream\Events\SocialProviderCreated;
use Laravel\Jetstream\Events\SocialProviderDeleted;
use Laravel\Jetstream\Events\SocialProviderUpdated;
use Laravel\Jetstream\Jetstream;

class SocialProvider extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'provider_name',
        'provider_id',
        'access_token',
        'refresh_token',
        'expires_at',
    ];

    /**
     * The event map for the model.
     *
     * @var array
     */
    protected $dispatchesEvents = [
        'created' => SocialProviderCreated::class,
        'updated' => SocialProviderUpdated::class,
        'deleted' => SocialProviderDeleted::class,
    ];

    /**
     * Get the owner of the provider.
     */
    public function user()
    {
        return $this->belongsTo(Jetstream::userModel(), 'user_id');
    }
}

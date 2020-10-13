<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Laravel\Jetstream\Events\ConnectedAccountCreated;
use Laravel\Jetstream\Events\ConnectedAccountDeleted;
use Laravel\Jetstream\Events\ConnectedAccountUpdated;
use Laravel\Jetstream\Jetstream;
use Laravel\Jetstream\ConnectedAccount as JetstreamConnectedAccount;

class ConnectedAccount extends JetstreamConnectedAccount
{
    use HasFactory;
    use HasTimestamps;

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
        'created' => ConnectedAccountCreated::class,
        'updated' => ConnectedAccountUpdated::class,
        'deleted' => ConnectedAccountDeleted::class,
    ];

    /**
     * Get the owner of the provider.
     */
    public function user()
    {
        return $this->belongsTo(Jetstream::userModel(), 'user_id');
    }
}
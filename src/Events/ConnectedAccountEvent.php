<?php

namespace Laravel\Jetstream\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class ConnectedAccountEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The social provider instance.
     *
     * @var \App\SocialProvider
     */
    public $provider;

    /**
     * Create a new event instance.
     *
     * @param  \App\SocialProvider  $provider
     * @return void
     */
    public function __construct($provider)
    {
        $this->provider = $provider;
    }
}

<?php

namespace Laravel\Jetstream\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

abstract class ConnectedAccountEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The connected account  instance.
     *
     * @var mixed
     */
    public $model;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $model
     * @return void
     */
    public function __construct($model)
    {
        $this->model = $model;
    }
}

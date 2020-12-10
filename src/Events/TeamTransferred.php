<?php

namespace Laravel\Jetstream\Events;

use Illuminate\Foundation\Events\Dispatchable;

class TeamTransferred
{
    use Dispatchable;

    /**
     * The team instance.
     *
     * @var mixed
     */
    public $team;

    /**
     * The original team owner.
     *
     * @var mixed
     */
    public $from;

    /**
     * The team member that the team was transferred to.
     *
     * @var mixed
     */
    public $to;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $team
     * @param  mixed  $from
     * @param  mixed  $to
     * @return void
     */
    public function __construct($team, $from, $to)
    {
        $this->team = $team;
        $this->from = $from;
        $this->to = $to;
    }
}

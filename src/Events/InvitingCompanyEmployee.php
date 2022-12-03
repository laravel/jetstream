<?php

namespace Laravel\Jetstream\Events;

use Illuminate\Foundation\Events\Dispatchable;

class InvitingCompanyEmployee
{
    use Dispatchable;

    /**
     * The company instance.
     *
     * @var mixed
     */
    public $company;

    /**
     * The email address of the invitee.
     *
     * @var mixed
     */
    public $email;

    /**
     * The role of the invitee.
     *
     * @var mixed
     */
    public $role;

    /**
     * Create a new event instance.
     *
     * @param  mixed  $company
     * @param  mixed  $email
     * @param  mixed  $role
     * @return void
     */
    public function __construct($company, $email, $role)
    {
        $this->company = $company;
        $this->email = $email;
        $this->role = $role;
    }
}

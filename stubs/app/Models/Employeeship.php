<?php

namespace App\Models;

use Laravel\Jetstream\Employeeship as JetstreamEmployeeship;

class Employeeship extends JetstreamEmployeeship
{
    /**
     * Indicates if the IDs are auto-incrementing.
     *
     * @var bool
     */
    public $incrementing = true;
}

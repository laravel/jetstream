<?php

namespace Laravel\Jetstream\Contracts;

interface AddsCompanyEmployees
{
    /**
     * Add a new company employee to the given company.
     *
     * @param  mixed  $user
     * @param  mixed  $company
     * @param  string  $email
     * @return void
     */
    public function add($user, $company, string $email, string $role = null);
}

<?php

namespace Laravel\Jetstream\Contracts;

interface CreatesCompanies
{
    /**
     * Validate and create a new company for the given user.
     *
     * @param  mixed  $user
     * @param  array  $input
     * @return mixed
     */
    public function create($user, array $input);
}

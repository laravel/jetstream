<?php

namespace Laravel\Jetstream\Contracts;

interface UpdatesCompanyNames
{
    /**
     * Validate and update the given company's name.
     *
     * @param  mixed  $user
     * @param  mixed  $company
     * @param  array  $input
     * @return void
     */
    public function update($user, $company, array $input);
}

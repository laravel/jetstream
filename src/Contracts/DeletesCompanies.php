<?php

namespace Laravel\Jetstream\Contracts;

interface DeletesCompanies
{
    /**
     * Delete the given company.
     *
     * @param  mixed  $company
     * @return void
     */
    public function delete($company);
}

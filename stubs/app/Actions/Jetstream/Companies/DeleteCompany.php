<?php

namespace App\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesCompanies;

class DeleteCompany implements DeletesCompanies
{
    /**
     * Delete the given company.
     *
     * @param  mixed  $company
     * @return void
     */
    public function delete($company)
    {
        $company->purge();
    }
}

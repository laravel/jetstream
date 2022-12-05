<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Company Settings') }}
        </h2>
    </x-slot>

    <div>
        <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
            @livewire('companies.update-company-name-form', ['company' => $company])

            @livewire('companies.company-employee-manager', ['company' => $company])

            @if (Gate::check('delete', $company) && ! $company->personal_company)
                <x-jet-section-border />

                <div class="mt-10 sm:mt-0">
                    @livewire('companies.delete-company-form', ['company' => $company])
                </div>
            @endif
        </div>
    </div>
</x-app-layout>

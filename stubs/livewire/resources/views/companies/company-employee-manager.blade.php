<div>
    @if (Gate::check('addCompanyEmployee', $company))
        <x-jet-section-border />

        <!-- Add Company Employee -->
        <div class="mt-10 sm:mt-0">
            <x-jet-form-section submit="addCompanyEmployee">
                <x-slot name="title">
                    {{ __('Add Company Employee') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Add a new company employee to your company, allowing them to collaborate with you on your business endeavors.') }}
                </x-slot>

                <x-slot name="form">
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            {{ __('Please provide the email address of the employee you would like to add to this company.') }}
                        </div>
                    </div>

                    <!-- Employee Email -->
                    <div class="col-span-6 sm:col-span-4">
                        <x-jet-label for="email" value="{{ __('Email') }}" />
                        <x-jet-input id="email" type="email" class="mt-1 block w-full" wire:model.defer="addCompanyEmployeeForm.email" />
                        <x-jet-input-error for="email" class="mt-2" />
                    </div>

                    <!-- Role -->
                    @if (count($this->roles) > 0)
                        <div class="col-span-6 lg:col-span-4">
                            <x-jet-label for="role" value="{{ __('Role') }}" />
                            <x-jet-input-error for="role" class="mt-2" />

                            <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                                @foreach ($this->roles as $index => $role)
                                    <button type="button" class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 {{ $index > 0 ? 'border-t border-gray-200 rounded-t-none' : '' }} {{ ! $loop->last ? 'rounded-b-none' : '' }}"
                                                    wire:click="$set('addCompanyEmployeeForm.role', '{{ $role->key }}')">
                                        <div class="{{ isset($addCompanyEmployeeForm['role']) && $addCompanyEmployeeForm['role'] !== $role->key ? 'opacity-50' : '' }}">
                                            <!-- Role Name -->
                                            <div class="flex items-center">
                                                <div class="text-sm text-gray-600 {{ $addCompanyEmployeeForm['role'] == $role->key ? 'font-semibold' : '' }}">
                                                    {{ $role->name }}
                                                </div>

                                                @if ($addCompanyEmployeeForm['role'] == $role->key)
                                                    <svg class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                @endif
                                            </div>

                                            <!-- Role Description -->
                                            <div class="mt-2 text-xs text-gray-600 text-left">
                                                {{ $role->description }}
                                            </div>
                                        </div>
                                    </button>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </x-slot>

                <x-slot name="actions">
                    <x-jet-action-message class="mr-3" on="saved">
                        {{ __('Added.') }}
                    </x-jet-action-message>

                    <x-jet-button>
                        {{ __('Add') }}
                    </x-jet-button>
                </x-slot>
            </x-jet-form-section>
        </div>
    @endif

    @if ($company->companyInvitations->isNotEmpty() && Gate::check('addCompanyEmployee', $company))
        <x-jet-section-border />

        <!-- Company Employee Invitations -->
        <div class="mt-10 sm:mt-0">
            <x-jet-action-section>
                <x-slot name="title">
                    {{ __('Pending Company Invitations') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('These employees have been invited to your company and have been sent an invitation email. They may join the company by accepting the email invitation.') }}
                </x-slot>

                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($company->companyInvitations as $invitation)
                            <div class="flex items-center justify-between">
                                <div class="text-gray-600">{{ $invitation->email }}</div>

                                <div class="flex items-center">
                                    @if (Gate::check('removeCompanyEmployee', $company))
                                        <!-- Cancel Company Invitation -->
                                        <button class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                                            wire:click="cancelCompanyInvitation({{ $invitation->id }})">
                                            {{ __('Cancel') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-jet-action-section>
        </div>
    @endif

    @if ($company->users->isNotEmpty())
        <x-jet-section-border />

        <!-- Manage Company Employees -->
        <div class="mt-10 sm:mt-0">
            <x-jet-action-section>
                <x-slot name="title">
                    {{ __('Company Employees') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('All of the employees that are part of this company.') }}
                </x-slot>

                <!-- Company Employee List -->
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($company->users->sortBy('name') as $user)
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <img class="w-8 h-8 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->name }}">
                                    <div class="ml-4">{{ $user->name }}</div>
                                </div>

                                <div class="flex items-center">
                                    <!-- Manage Company Employee Role -->
                                    @if (Gate::check('addCompanyEmployee', $company) && Laravel\Jetstream\Jetstream::hasRoles())
                                        <button class="ml-2 text-sm text-gray-400 underline" wire:click="manageRole('{{ $user->id }}')">
                                            {{ Laravel\Jetstream\Jetstream::findRole($user->employee->role)->name }}
                                        </button>
                                    @elseif (Laravel\Jetstream\Jetstream::hasRoles())
                                        <div class="ml-2 text-sm text-gray-400">
                                            {{ Laravel\Jetstream\Jetstream::findRole($user->employee->role)->name }}
                                        </div>
                                    @endif

                                    <!-- Leave Company -->
                                    @if ($this->user->id === $user->id)
                                        <button class="cursor-pointer ml-6 text-sm text-red-500" wire:click="$toggle('confirmingLeavingCompany')">
                                            {{ __('Leave') }}
                                        </button>

                                    <!-- Remove Company Employee -->
                                    @elseif (Gate::check('removeCompanyEmployee', $company))
                                        <button class="cursor-pointer ml-6 text-sm text-red-500" wire:click="confirmCompanyEmployeeRemoval('{{ $user->id }}')">
                                            {{ __('Remove') }}
                                        </button>
                                    @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-jet-action-section>
        </div>
    @endif

    <!-- Role Management Modal -->
    <x-jet-dialog-modal wire:model="currentlyManagingRole">
        <x-slot name="title">
            {{ __('Manage Role') }}
        </x-slot>

        <x-slot name="content">
            <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                @foreach ($this->roles as $index => $role)
                    <button type="button" class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200 {{ $index > 0 ? 'border-t border-gray-200 rounded-t-none' : '' }} {{ ! $loop->last ? 'rounded-b-none' : '' }}"
                                    wire:click="$set('currentRole', '{{ $role->key }}')">
                        <div class="{{ $currentRole !== $role->key ? 'opacity-50' : '' }}">
                            <!-- Role Name -->
                            <div class="flex items-center">
                                <div class="text-sm text-gray-600 {{ $currentRole == $role->key ? 'font-semibold' : '' }}">
                                    {{ $role->name }}
                                </div>

                                @if ($currentRole == $role->key)
                                    <svg class="ml-2 h-5 w-5 text-green-400" fill="none" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" stroke="currentColor" viewBox="0 0 24 24"><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                @endif
                            </div>

                            <!-- Role Description -->
                            <div class="mt-2 text-xs text-gray-600">
                                {{ $role->description }}
                            </div>
                        </div>
                    </button>
                @endforeach
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="stopManagingRole" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-button class="ml-3" wire:click="updateRole" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-jet-button>
        </x-slot>
    </x-jet-dialog-modal>

    <!-- Leave Company Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingLeavingCompany">
        <x-slot name="title">
            {{ __('Leave Company') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to leave this company?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingLeavingCompany')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-3" wire:click="leaveCompany" wire:loading.attr="disabled">
                {{ __('Leave') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>

    <!-- Remove Company Employee Confirmation Modal -->
    <x-jet-confirmation-modal wire:model="confirmingCompanyEmployeeRemoval">
        <x-slot name="title">
            {{ __('Remove Company Employee') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to remove this employee from the company?') }}
        </x-slot>

        <x-slot name="footer">
            <x-jet-secondary-button wire:click="$toggle('confirmingCompanyEmployeeRemoval')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-jet-secondary-button>

            <x-jet-danger-button class="ml-3" wire:click="removeCompanyEmployee" wire:loading.attr="disabled">
                {{ __('Remove') }}
            </x-jet-danger-button>
        </x-slot>
    </x-jet-confirmation-modal>
</div>

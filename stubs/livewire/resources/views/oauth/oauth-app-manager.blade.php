<div>
    <!-- Register OAuth App -->
    <x-form-section submit="createOAuthApp">
        <x-slot name="title">
            {{ __('Register OAuth App') }}
        </x-slot>

        <x-slot name="description">
            {{ __('You may register an OAuth client to use our application\'s API.') }}
        </x-slot>

        <x-slot name="form">
            <!-- App Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('Application Name') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="createOAuthAppForm.name" autofocus autocomplete="off" />
                <x-input-error for="name" bag="createClient" class="mt-2" />
            </div>

            <!-- Authorization Redirect URI -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="redirect_uri" value="{{ __('Authorization Redirect URI') }}" />
                <x-input id="redirect_uri" type="url" class="mt-1 block w-full" wire:model="createOAuthAppForm.redirect_uris.0" autocomplete="off" />
                <x-input-error for="redirect_uris" bag="createClient" class="mt-2" />
            </div>

            <!-- Confidential -->
            <div class="col-span-6 sm:col-span-4">
                <label for="confidential" class="flex items-center">
                    <x-checkbox id="confidential" name="confidential" wire:model="createOAuthAppForm.confidential" />
                    <span class="ms-2 font-medium text-sm text-gray-700 dark:text-gray-400">{{ __('Confidential') }}</span>
                </label>
                <x-input-error for="confidential" bag="createClient" class="mt-2" />
            </div>
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="app-created">
                {{ __('Registered.') }}
            </x-action-message>

            <x-button>
                {{ __('Register') }}
            </x-button>
        </x-slot>
    </x-form-section>

    @if ($this->apps->isNotEmpty())
        <x-section-border />

        <!-- Manage OAuth Apps -->
        <div class="mt-10 sm:mt-0">
            <x-action-section>
                <x-slot name="title">
                    {{ __('Manage OAuth Apps') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('You may delete any of your existing registered apps if they are no longer needed.') }}
                </x-slot>

                <!-- OAuth App List -->
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($this->apps as $app)
                            <div class="flex items-center justify-between" wire:key="{{ $app->id }}">
                                <div class="dark:text-white">
                                    {{ $app->name }}
                                    <span class="text-sm italic text-gray-400">
                                        &ndash; {{ $app->confidential() ? __('Confidential') : __('Public') }}
                                    </span>
                                </div>

                                <div class="flex items-center ms-2">
                                    <div class="text-sm text-gray-400">
                                        {{ __('Created at') }} {{ $app->created_at->toFormattedDateString() }}
                                    </div>

                                    <button class="cursor-pointer ms-6 text-sm text-gray-400 underline" wire:click="manageOAuthApp('{{ $app->id }}')">
                                        {{ __('Manage') }}
                                    </button>

                                    <button class="cursor-pointer ms-6 text-sm text-red-500" wire:click="confirmOAuthAppDeletion('{{ $app->id }}')">
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-action-section>
        </div>
    @endif

    <!-- Client Credentials Modal -->
    <x-dialog-modal wire:model.live="displayingClientCredentials">
        <x-slot name="title">
            {{ __('Client Credentials') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 gap-4">
                <div>
                    {{ __('Please copy your new client credentials.') }}

                    @if ($clientCredentials['secret'])
                        {{ __('For your security, client secret won\'t be shown again.') }}
                    @endif
                </div>

                <div>
                    <x-label for="client_id" value="{{ __('Client ID') }}" />
                    <x-input x-ref="clientIdField" id="client_id" type="text" readonly :value="$clientCredentials['id']"
                             class="mt-1 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full break-all"
                             autofocus autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                             @client-credentials-displayed.window="setTimeout(() => $refs.clientIdField.select(), 250)"
                    />
                </div>

                @if ($clientCredentials['secret'])
                    <div>
                        <x-label for="client_secret" value="{{ __('Client Secret') }}" />
                        <x-input id="client_secret" type="text" readonly :value="$clientCredentials['secret']"
                                 class="mt-1 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full break-all"
                                 autofocus autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                        />
                    </div>
                @endif
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('displayingClientCredentials', false)" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete OAuth App Confirmation Modal -->
    <x-confirmation-modal wire:model.live="confirmingOAuthAppDeletion">
        <x-slot name="title">
            {{ __('Delete OAuth App') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this app?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingOAuthAppDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteOAuthApp" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>

    <!-- OAuth App Management Modal -->
    <x-dialog-modal wire:model.live="managingOAuthApp">
        <x-slot name="title">
            {{ __('OAuth App Management') }}
        </x-slot>

        <x-slot name="content">
            <div class="grid grid-cols-1 gap-4">
                <!-- Client ID -->
                <div>
                    <x-label for="manage_client_id" value="{{ __('Client ID') }}" />
                    <x-input id="manage_client_id" type="text" readonly :value="$oauthAppBeingManaged?->id"
                             class="mt-1 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full break-all"
                    />
                </div>

                <!-- App Name -->
                <div>
                    <x-label for="manage_name" value="{{ __('Application Name') }}" />
                    <x-input id="manage_name" type="text" class="mt-1 block w-full" wire:model="updateOAuthAppForm.name" autocomplete="off" />
                    <x-input-error for="name" class="mt-2" />
                </div>

                <!-- Authorization Redirect URI -->
                <div>
                    <x-label for="manage_redirect_uri" value="{{ __('Authorization Redirect URI') }}" />
                    <x-input id="manage_redirect_uri" type="url" class="mt-1 block w-full" wire:model="updateOAuthAppForm.redirect_uris.0" autocomplete="off" />
                    <x-input-error for="redirect_uris" class="mt-2" />
                </div>
            </div>
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('managingOAuthApp', false)" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-button class="ms-3" wire:click="updateOAuthApp" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-dialog-modal>
</div>

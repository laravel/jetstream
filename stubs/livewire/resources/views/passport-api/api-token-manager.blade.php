<div>
    <!-- Generate API Token -->
    <x-form-section submit="createApiToken">
        <x-slot name="title">
            {{ __('Create API Token') }}
        </x-slot>

        <x-slot name="description">
            {{ __('Personal access tokens allow secure authentication to our application\'s API for your personal use.') }}
        </x-slot>

        <x-slot name="form">
            <!-- Token Name -->
            <div class="col-span-6 sm:col-span-4">
                <x-label for="name" value="{{ __('Token Name') }}" />
                <x-input id="name" type="text" class="mt-1 block w-full" wire:model="createApiTokenForm.name" autofocus autocomplete="off" />
                <x-input-error for="name" class="mt-2" />
            </div>

            <!-- Token Scopes -->
            @if (count($scopes = Laravel\Passport\Passport::scopes()) > 0)
                <div class="col-span-6">
                    <fieldset>
                        <legend class="block font-medium text-sm text-gray-700 dark:text-gray-300">{{ __('Scopes') }}</legend>

                        <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                            @foreach ($scopes as $scope)
                                <label class="flex items-center" wire:key="{{ $scope->id }}">
                                    <x-checkbox wire:model="createApiTokenForm.scopes" :value="$scope->id"/>
                                    <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">{{ $scope->description }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                </div>
            @endif
        </x-slot>

        <x-slot name="actions">
            <x-action-message class="me-3" on="token-created">
                {{ __('Created.') }}
            </x-action-message>

            <x-button>
                {{ __('Create') }}
            </x-button>
        </x-slot>
    </x-form-section>

    @if ($this->tokens->isNotEmpty())
        <x-section-border />

        <!-- Manage API Tokens -->
        <div class="mt-10 sm:mt-0">
            <x-action-section>
                <x-slot name="title">
                    {{ __('Manage API Tokens') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('You may delete any of your existing personal access tokens if they are no longer needed.') }}
                </x-slot>

                <!-- API Token List -->
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($this->tokens as $token)
                            <div class="flex items-center justify-between" wire:key="{{ $token->id }}">
                                <div>
                                    <div class="dark:text-white">
                                        {{ $token->name }}
                                    </div>
                                    <div class="text-sm italic text-gray-500">
                                        {{ implode(', ', $token->scopes) }}
                                    </div>
                                </div>

                                <div class="flex items-center ms-2">
                                    <div class="text-sm text-gray-400">
                                        {{ __('Issued') }} {{ $token->created_at->diffForHumans() }}
                                    </div>

                                    <div class="ms-6 text-sm text-gray-400">
                                        {{ __('Expires in') }} {{ $token->expires_at->longAbsoluteDiffForHumans() }}
                                    </div>

                                    <button class="cursor-pointer ms-6 text-sm text-red-500" wire:click="confirmApiTokenDeletion('{{ $token->id }}')">
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

    <!-- Token Value Modal -->
    <x-dialog-modal wire:model.live="displayingToken">
        <x-slot name="title">
            {{ __('Personal Access Token') }}
        </x-slot>

        <x-slot name="content">
            <div>
                {{ __('Please copy your new personal access token. For your security, it won\'t be shown again.') }}
            </div>

            <x-input x-ref="tokenField" id="token" type="text" readonly :value="$tokenValue"
                     class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500 w-full break-all"
                     autofocus autocomplete="off" autocorrect="off" autocapitalize="off" spellcheck="false"
                     @token-displayed.window="setTimeout(() => $refs.tokenField.select(), 250)"
            />
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$set('displayingToken', false)" wire:loading.attr="disabled">
                {{ __('Close') }}
            </x-secondary-button>
        </x-slot>
    </x-dialog-modal>

    <!-- Delete Token Confirmation Modal -->
    <x-confirmation-modal wire:model.live="confirmingApiTokenDeletion">
        <x-slot name="title">
            {{ __('Delete API Token') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete this API token?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingApiTokenDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteApiToken" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>

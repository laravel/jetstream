<x-jet-action-section>
    <x-slot name="title">
        {{ __('Connected Accounts') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage and remove your connect accounts.') }}
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            {{ __('You may disconnect any of your connected accounts below. If you feel any of your connected accounts have been compromised, you should disconnect them immediately.') }}
        </div>

        @if (count($this->providers) > 0)
            <div class="mt-5 space-y-6 grid grid-cols-3">
                @foreach($this->providers as $provider)
                    <div class="p-3 flex items-center justify-between col-span-1">
                        <!-- Provider Info -->
                        <div>
                            <!-- Provider SVG Logo -->
                            <div>

                            </div>

                            <!-- Provider details -->
                            <div>
                                <div class="text-sm font-semibold text-gray-600">
                                    {{ IlluminateSupportStr::ucfirst($provider->provider_name) }}
                                </div>

                                <div>
                                    <div class="text-xs text-gray-500">
                                        {{ __('Added on') }} {{ $provider->created_at->format('d/m/Y') }}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <x-jet-button wire:click="confirmRemove" wire:loading.attr="disabled" class="bg-red-600 hover:bg-red-500">
                            {{ __('Remove') }}
                        </x-jet-button>
                    </div>
                @endforeach
            </div>
    @else
    @endif

    <!-- Logout Other Devices Confirmation Modal -->
        <x-jet-dialog-modal wire:model="confirmingRemove">
            <x-slot name="title">
                {{ __('Remove Connected Account') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Please enter your password to confirm you would like to remove this connected account.') }}

                <div class="mt-4" x-data="{}" x-on:confirming-remove-oauth-provider.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-jet-input type="password" class="mt-1 block w-3/4" placeholder="{{ __('Password') }}"
                                 x-ref="password"
                                 wire:model.defer="password"
                                 wire:keydown.enter="removeConnectedAccount({{ $provider->id }})" />

                    <x-jet-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingRemove')" wire:loading.attr="disabled">
                    {{ __('Nevermind') }}
                </x-jet-secondary-button>

                <x-jet-button class="ml-2 bg-red-600 hover:bg-red-500" wire:click="removeConnectedAccount({{ $provider->id }})" wire:loading.attr="disabled">
                    {{ __('Remove Connected Account') }}
                </x-jet-button>
            </x-slot>
        </x-jet-dialog-modal>
    </x-slot>
</x-jet-action-section>

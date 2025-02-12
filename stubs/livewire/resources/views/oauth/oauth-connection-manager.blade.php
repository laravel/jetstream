<div>
    @if ($this->connections->isNotEmpty())
        <!-- Manage OAuth Connections -->
        <div class="mt-10 sm:mt-0">
            <x-action-section>
                <x-slot name="title">
                    {{ __('Manage Authorized Apps') }}
                </x-slot>

                <x-slot name="description">
                    {{ __('Keep track of your connections with third-party apps and services. You may delete the access you\'ve given to any of your existing authorized apps if they are no longer needed.') }}
                </x-slot>

                <!-- OAuth Connection List -->
                <x-slot name="content">
                    <div class="space-y-6">
                        @foreach ($this->connections as $connection)
                            <div class="flex items-center justify-between" wire:key="{{ $connection['client']->id }}">
                                <div>
                                    <div>
                                        {{ $connection['client']->name }}
                                    </div>
                                    <div class="text-sm italic text-gray-500">
                                        {{ implode(', ', $connection['scopes']) }}
                                    </div>
                                </div>

                                <div class="flex items-center ms-2">
                                    <div class="text-sm text-gray-400">
                                        {{ $connection['tokens_count'] }} {{ __('Tokens') }}
                                    </div>

                                    <button class="cursor-pointer ms-6 text-sm text-red-500" wire:click="confirmConnectionDeletion('{{ $connection['client']->id }}')">
                                        {{ __('Delete') }}
                                    </button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </x-slot>
            </x-action-section>
        </div>

        <x-section-border />
    @endif

    <!-- Delete OAuth Connection Confirmation Modal -->
    <x-confirmation-modal wire:model.live="confirmingConnectionDeletion">
        <x-slot name="title">
            {{ __('Delete OAuth Connection') }}
        </x-slot>

        <x-slot name="content">
            {{ __('Are you sure you would like to delete all connections you have with this app?') }}
        </x-slot>

        <x-slot name="footer">
            <x-secondary-button wire:click="$toggle('confirmingConnectionDeletion')" wire:loading.attr="disabled">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button class="ms-3" wire:click="deleteConnection" wire:loading.attr="disabled">
                {{ __('Delete') }}
            </x-danger-button>
        </x-slot>
    </x-confirmation-modal>
</div>

<x-jet-action-section>
    <x-slot name="title">
        {{ __('Connected Accounts') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Manage and remove your connect accounts.') }}
    </x-slot>

    <x-slot name="content">
        <h3 class="text-lg font-medium text-gray-900">
            @if (count($this->accounts) == 0)
                {{ __('You have no connected accounts.') }}
            @else
                {{ __('Your connected accounts.') }}
            @endif
        </h3>

        <div class="mt-3 max-w-xl text-sm text-gray-600">
            {{ __('You are free to connect any social accounts to your profile and may remove any connected accounts at any time. If you feel any of your connected accounts have been compromised, you should disconnect them immediately and change your password.') }}
        </div>

        <div class="mt-5 space-y-6">
            @foreach ($this->providers as $provider => $enabled)
                @if ($account = $this->accounts->where('provider_name', $provider)->first())
                    <x-jet-connected-account provider="{{ $account->provider_name }}" created-at="{{ $account->created_at }}">

                        <x-slot name="action">
                            @if ($this->accounts->count() > 1 || ! is_null($this->user->password))
                                <x-jet-button wire:click="confirmRemove({{ $account->id }})" wire:loading.attr="disabled">
                                    {{ __('Remove') }}
                                </x-jet-button>
                            @endif
                        </x-slot>

                    </x-jet-connected-account>
                @elseif ($enabled)
                    <x-jet-connected-account provider="{{ $provider }}" created-at="Not connected">
                        <x-slot name="action">
                            <x-jet-action-link href="{{ route('socialite.redirect', ['provider' => $provider]) }}">
                                {{ __('Connect') }}
                            </x-jet-action-link>
                        </x-slot>
                    </x-jet-connected-account>
                @endif
            @endforeach
        </div>

        <!-- Logout Other Devices Confirmation Modal -->
        <x-jet-dialog-modal wire:model="confirmingRemove">
            <x-slot name="title">
                {{ __('Remove Connected Account') }}
            </x-slot>

            <x-slot name="content">
                {{ __('Please confirm your removal of this account - this action cannot be undone.') }}
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingRemove')" wire:loading.attr="disabled">
                    {{ __('Nevermind') }}
                </x-jet-secondary-button>

                <x-jet-button class="ml-2" wire:click="removeConnectedAccount({{ $this->selectedAccountId }})" wire:loading.attr="disabled">
                    {{ __('Remove Connected Account') }}
                </x-jet-button>
            </x-slot>
        </x-jet-dialog-modal>
    </x-slot>
</x-jet-action-section>

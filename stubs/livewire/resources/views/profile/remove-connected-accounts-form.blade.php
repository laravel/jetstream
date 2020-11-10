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
            @if (count($this->accounts) == 0)
                {{ __('When you have one or more connected accounts, they will appear below. You may disconnect any of your connected accounts at any time. If you feel any of your connected accounts have been compromised, you should disconnect them immediately and change your password.') }}
            @else
                {{ __('You may disconnect any of your connected accounts below at any time. If you feel any of your connected accounts have been compromised, you should disconnect them immediately and change your password.') }}
            @endif
        </div>

        @if (count($this->accounts) > 0)
            <div class="mt-5 space-y-6">
                @foreach($this->accounts as $account)
                    <div class="p-3 flex items-center justify-between">
                        <div class="flex items-center">

                            @switch($account->provider_name)
                                @case('facebook')
                                    <x-jet-facebook-icon class="h-6 w-6 mr-2" />
                                    @break
                                @case('google')
                                    <x-jet-google-icon class="h-6 w-6 mr-2" />
                                    @break
                                @case('twitter')
                                    <x-jet-twitter-icon class="h-6 w-6 mr-2" />
                                    @break
                                @case('linkedin')
                                    <x-jet-linkedin-icon class="h-6 w-6 mr-2" />
                                    @break
                                @case('github')
                                    <x-jet-github-icon class="h-6 w-6 mr-2" />
                                    @break
                                @case('gitlab')
                                    <x-jet-gitlab-icon class="h-6 w-6 mr-2" />
                                    @break
                                @case('bitbucket')
                                    <x-jet-bitbucket-icon class="h-6 w-6 mr-2" />
                                    @break
                                @default
                            @endswitch

                            <div>
                                <div class="text-sm font-semibold text-gray-600">
                                    {{ ucfirst($account->provider_name) }}
                                </div>

                                <div class="text-xs text-gray-500">
                                    Added on {{ $account->created_at }}
                                </div>
                            </div>
                        </div>

                        <!-- Remove Action -->
                        @if(count($this->accounts) > 1)
                            <x-jet-button wire:click="confirmRemove({{ $account->id }})" wire:loading.attr="disabled">
                                {{ __('Remove') }}
                            </x-jet-button>
                        @endif
                    </div>
                @endforeach
            </div>
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
                                 wire:keydown.enter="removeConnectedAccount({{ $this->selectedAccountId }})" />

                    <x-jet-input-error for="password" class="mt-2" />
                </div>
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

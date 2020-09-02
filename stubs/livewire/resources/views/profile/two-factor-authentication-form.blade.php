<x-jet-action-section>
    <x-slot name="title">
        Two Factor Authentication
    </x-slot>

    <x-slot name="description">
        Add additional security to your account using two factor authentication.
    </x-slot>

    <x-slot name="content">
        <h3 class="text-lg font-medium text-gray-900">
            @if ($this->enabled)
                You have enabled two factor authentication.
            @else
                You have not enabled two factor authentication.
            @endif
        </h3>

        <div class="mt-3 max-w-xl text-sm text-gray-600">
            <p>
                When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
            </p>
        </div>

        @if ($this->enabled)
            @if ($showingQrCode)
                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.
                    </p>
                </div>

                <div class="mt-4">
                    {!! $this->user->twoFactorQrCodeSvg() !!}
                </div>
            @endif

            @if ($showingRecoveryCodes)
                <div class="mt-4 max-w-xl text-sm text-gray-600">
                    <p class="font-semibold">
                        Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
                    </p>
                </div>

                <div class="grid gap-1 max-w-xl mt-4 px-4 py-4 font-mono text-sm bg-gray-100 rounded-lg">
                    @foreach (json_decode(decrypt($this->user->two_factor_recovery_codes), true) as $code)
                        <div>{{ $code }}</div>
                    @endforeach
                </div>
            @endif
        @endif

        <div class="mt-5">
            @if (! $this->enabled)
                <x-jet-button type="button" wire:click="confirmEnable" wire:loading.attr="disabled">
                    Enable
                </x-jet-button>
            @else
                @if ($showingRecoveryCodes)
                    <x-jet-secondary-button class="mr-3" wire:click="confirmRegenerate" wire:loading.attr="disabled">
                        Regenerate Recovery Codes
                    </x-jet-secondary-button>
                @else
                    <x-jet-secondary-button class="mr-3" wire:click="$toggle('showingRecoveryCodes')">
                        Show Recovery Codes
                    </x-jet-secondary-button>
                @endif

                <x-jet-danger-button wire:click="confirmDisable" wire:loading.attr="disabled">
                    Disable
                </x-jet-danger-button>
            @endif
        </div>

        <!-- Enable two factor authentication Confirmation Modal -->
        <x-jet-dialog-modal show="confirmingEnableTwoFactorAuthentication">
            <x-slot name="title">
                Enable Two Factor Authentication
            </x-slot>

            <x-slot name="content">
                Please enter your password to confirm you would like to enable Two Factor Authentication.

                <div class="mt-4" x-data="{}" x-on:confirming-enable-two-factor-authentication.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-jet-input type="password" class="mt-1 block w-3/4" placeholder="Password"
                                 x-ref="password"
                                 wire:model="password" />

                    <x-jet-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingEnableTwoFactorAuthentication')" wire:loading.class="opacity-25" wire:loading.attr="disabled">
                    Nevermind
                </x-jet-secondary-button>

                <x-jet-button class="ml-2" wire:click="enableTwoFactorAuthentication" wire:loading.class="opacity-25" wire:loading.attr="disabled">
                    Enable Two Factor Authentication
                </x-jet-button>
            </x-slot>
        </x-jet-dialog-modal>

        <!-- Disable Two Factor Authentication Confirmation Modal -->
        <x-jet-dialog-modal show="confirmingDisableTwoFactorAuthentication">
            <x-slot name="title">
                Disable Two Factor Authentication
            </x-slot>

            <x-slot name="content">
                Please enter your password to confirm you would like to disable Two Factor Authentication.

                <div class="mt-4" x-data="{}" x-on:confirming-disable-two-factor-authentication.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-jet-input type="password" class="mt-1 block w-3/4" placeholder="Password"
                                 x-ref="password"
                                 wire:model="password" />

                    <x-jet-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingDisableTwoFactorAuthentication')" wire:loading.class="opacity-25" wire:loading.attr="disabled">
                    Nevermind
                </x-jet-secondary-button>

                <x-jet-button class="ml-2" wire:click="disableTwoFactorAuthentication" wire:loading.class="opacity-25" wire:loading.attr="disabled">
                    Disable Two Factor Authentication
                </x-jet-button>
            </x-slot>
        </x-jet-dialog-modal>

        <!-- Regenerate Recovery Codes Confirmation Modal -->
        <x-jet-dialog-modal show="confirmingRegenerateRecoveryCodes">
            <x-slot name="title">
                Regenerate Recovery Codes
            </x-slot>

            <x-slot name="content">
                Please enter your password to confirm you would like to regenerate the recovery codes.

                <div class="mt-4" x-data="{}" x-on:confirming-regenerate-recovery-codes.window="setTimeout(() => $refs.password.focus(), 250)">
                    <x-jet-input type="password" class="mt-1 block w-3/4" placeholder="Password"
                                 x-ref="password"
                                 wire:model="password" />

                    <x-jet-input-error for="password" class="mt-2" />
                </div>
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingRegenerateRecoveryCodes')" wire:loading.class="opacity-25" wire:loading.attr="disabled">
                    Nevermind
                </x-jet-secondary-button>

                <x-jet-button class="ml-2" wire:click="regenerateRecoveryCodes" wire:loading.class="opacity-25" wire:loading.attr="disabled">
                    Regenerate Recovery Codes
                </x-jet-button>
            </x-slot>
        </x-jet-dialog-modal>

    </x-slot>
</x-jet-action-section>

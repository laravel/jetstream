<x-jet-action-section>
    <x-slot name="title">
        Delete Account
    </x-slot>

    <x-slot name="description">
        Permanently delete your account.
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.
        </div>

        <div class="mt-5">
            <x-jet-danger-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                Delete Account
            </x-jet-danger-button>
        </div>

        <!-- Delete User Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingUserDeletion">
            <x-slot name="title">
                Delete Account
            </x-slot>

            <x-slot name="content">
                Are you sure you want to delete your account? Once your account is deleted, all of its resources and data will be permanently deleted.
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingUserDeletion')" wire:loading.attr="disabled">
                    Nevermind
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="deleteUser" wire:loading.attr="disabled">
                    Delete Account
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>
    </x-slot>
</x-jet-action-section>

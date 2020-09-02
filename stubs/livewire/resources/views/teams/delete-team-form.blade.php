<x-jet-action-section>
    <x-slot name="title">
        Delete Team
    </x-slot>

    <x-slot name="description">
        Permanently delete this team.
    </x-slot>

    <x-slot name="content">
        <div class="max-w-xl text-sm text-gray-600">
            Once a team is deleted, all of its resources and data will be permanently deleted. Before deleting this team, please download any data or information regrading this team that you wish to retain.
        </div>

        <div class="mt-5">
            <x-jet-danger-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                Delete Team
            </x-jet-danger-button>
        </div>

        <!-- Delete Team Confirmation Modal -->
        <x-jet-confirmation-modal wire:model="confirmingTeamDeletion">
            <x-slot name="title">
                Delete Team
            </x-slot>

            <x-slot name="content">
                Are you sure you want to delete this team? Once a team is deleted, all of its resources and data will be permanently deleted.
            </x-slot>

            <x-slot name="footer">
                <x-jet-secondary-button wire:click="$toggle('confirmingTeamDeletion')" wire:loading.attr="disabled">
                    Nevermind
                </x-jet-secondary-button>

                <x-jet-danger-button class="ml-2" wire:click="deleteTeam" wire:loading.attr="disabled">
                    Delete Team
                </x-jet-danger-button>
            </x-slot>
        </x-jet-confirmation-modal>
    </x-slot>
</x-jet-action-section>

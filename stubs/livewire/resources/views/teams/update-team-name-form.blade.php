<x-jet-form-section submit="updateTeamName">
    <x-slot name="title">
        Team Name
    </x-slot>

    <x-slot name="description">
        The team's name and owner information.
    </x-slot>

    <x-slot name="form">
        <!-- Team Owner Information -->
        <div class="col-span-6">
            <x-jet-label value="Team Owner" />

            <div class="flex items-center mt-2">
                <img class="w-12 h-12 rounded-full" src="{{ $team->owner->profile_photo_url }}">

                <div class="ml-4 leading-tight">
                    <div>{{ $team->owner->name }}</div>
                    <div class="text-gray-700 text-sm">{{ $team->owner->email }}</div>
                </div>
            </div>
        </div>

        <!-- Team Name -->
        <div class="col-span-6 sm:col-span-4">
            <x-jet-label for="name" value="Team Name" />

            <x-jet-input id="name"
                        type="text"
                        class="mt-1 block w-full"
                        wire:model.defer="state.name"
                        :disabled="! Gate::check('update', $team)" />

            <x-jet-input-error for="name" class="mt-2" />
        </div>
    </x-slot>

    @if (Gate::check('update', $team))
        <x-slot name="actions">
            <x-jet-action-message class="mr-3" on="saved">
                Saved.
            </x-jet-action-message>

            <x-jet-button>
                Save
            </x-jet-button>
        </x-slot>
    @endif
</x-jet-form-section>

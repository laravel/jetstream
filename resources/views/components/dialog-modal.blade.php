@props(['id' => null, 'maxWidth' => null])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="text-lg">
            {{ $title }}
        </div>

        <div class="mt-4">
            {{ $content }}
        </div>
    </div>

    <div class="px-6 py-4 bg-gray-100 text-right flex flex-col flex-col-reverse sm:items-center sm:justify-end sm:flex-row sm:space-x-6 space-y-reverse space-y-4 sm:space-y-0">
        {{ $footer }}
    </div>
</x-jet-modal>

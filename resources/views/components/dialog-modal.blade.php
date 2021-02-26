@props(['id' => null, 'maxWidth' => null])

<x-jet-modal :id="$id" :maxWidth="$maxWidth" {{ $attributes }}>
    <div class="px-6 py-4">
        <div class="text-lg dark:text-gray-200">
            {{ $title }}
        </div>

        <div class="mt-4 dark:text-gray-400">
            {{ $content }}
        </div>
    </div>

    <div class="px-6 py-4 text-right bg-gray-100 dark:text-gray-700">
        {{ $footer }}
    </div>
</x-jet-modal>

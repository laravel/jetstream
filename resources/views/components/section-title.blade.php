<div class="md:col-span-1">
    <div class="md:flex">
        <div class="px-4 sm:px-0 md:flex-grow">
            <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>

            <p class="mt-1 text-sm text-gray-600">
                {{ $description }}
            </p>
        </div>
        <template x-if="show !== undefined">
            <div class="cursor-pointer px-4 md:flex-grow-0" @click="show = ! show">
                <svg x-show="show" viewBox="0 0 448 512" fill="currentColor" class="h-5 w-5 text-gray-400"><path d="M207.029 381.476L12.686 187.132c-9.373-9.373-9.373-24.569 0-33.941l22.667-22.667c9.357-9.357 24.522-9.375 33.901-.04L224 284.505l154.745-154.021c9.379-9.335 24.544-9.317 33.901.04l22.667 22.667c9.373 9.373 9.373 24.569 0 33.941L240.971 381.476c-9.373 9.372-24.569 9.372-33.942 0z"></path></svg>
                <svg x-show="! show" viewBox="0 0 320 512" fill="currentColor" class="h-5 w-5 text-gray-400"><path d="M285.476 272.971L91.132 467.314c-9.373 9.373-24.569 9.373-33.941 0l-22.667-22.667c-9.357-9.357-9.375-24.522-.04-33.901L188.505 256 34.484 101.255c-9.335-9.379-9.317-24.544.04-33.901l22.667-22.667c9.373-9.373 24.569-9.373 33.941 0L285.475 239.03c9.373 9.372 9.373 24.568.001 33.941z"></path></svg>
            </div>
        </template>
    </div>
</div>

@props(['on'])

<div x-data="{ shown: false, timeout: null }"
    x-on:{{ $on }}.window="clearTimeout(timeout); shown = true; timeout = setTimeout(() => { shown = false }, 2000);"
    x-show.transition.out.opacity.duration.1500ms="shown"
    x-transition:leave.opacity.duration.1500ms
    style="display: none;"
    {{ $attributes->merge(['class' => 'text-sm text-gray-600 dark:text-gray-400']) }}>
    {{ $slot->isEmpty() ? 'Saved.' : $slot }}
</div>

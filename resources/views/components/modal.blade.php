@props(['id' => null, 'show', 'maxWidth'])

@php
if (is_null($id)) {
    $id = md5($show);
}

if (is_bool($_instance->{$show})) {
    $shouldShow = $_instance->{$show};
} else {
    $shouldShow = ! is_null($_instance->{$show});
}

switch ($maxWidth ?? '2xl') {
    case 'sm':
        $maxWidth = 'sm:max-w-sm';
        break;
    case 'md':
        $maxWidth = 'sm:max-w-md';
        break;
    case 'lg':
        $maxWidth = 'sm:max-w-lg';
        break;
    case 'xl':
        $maxWidth = 'sm:max-w-xl';
        break;
    case '2xl':
    default:
        $maxWidth = 'sm:max-w-2xl';
        break;
}
@endphp

<div id="{{ $id }}" x-data="{ show: {{ var_export($shouldShow ?? false, true) }} }"
        x-show="show"
        x-on:close.stop="@this.set('{{ $show }}', false)"
        x-on:keydown.escape.window="if (! show) { return; } @this.set('{{ $show }}', false)"
        class="fixed top-0 inset-x-0 px-4 pt-6 sm:px-0 sm:flex sm:items-top sm:justify-center"
        style="display: none;">
    <div x-show="show" class="fixed inset-0 transform transition-all" x-on:click="@this.set('{{ $show }}', false)" x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0">
        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
    </div>

    <div x-show="show" class="bg-white rounded-lg overflow-hidden shadow-xl transform transition-all sm:w-full {{ $maxWidth }}"
                    x-transition:enter="ease-out duration-300"
                    x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
                    x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave="ease-in duration-200"
                    x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
                    x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95">
        {{ $slot }}
    </div>
</div>

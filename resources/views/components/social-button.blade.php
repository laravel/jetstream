@props(['href', 'color' => null])

<a href="{{ $href }}" @if(! is_null($color)) style="color: {{ $color }};" @endif>
    {{ $slot }}
</a>

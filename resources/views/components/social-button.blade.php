@props(['color' => null])

<a href="" @if(! is_null($color)) style="color: {{ $color }};" @endif>
    {{ $slot }}
</a>

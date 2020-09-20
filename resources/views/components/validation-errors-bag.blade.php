@props(['bag'])

@if ($errors->hasBag($bag))
    <div {{ $attributes }}>
        <div class="font-medium text-red-600">{{ __('Please, fix the following errors') }}</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            @foreach ($errors->$bag->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

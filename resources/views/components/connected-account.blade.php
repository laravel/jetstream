@props(['provider', 'createdAt'])

<div class="px-3 flex items-center justify-between">
    <div class="flex items-center">
        @switch($provider)
            @case('facebook')
                <x-jet-facebook-icon class="h-6 w-6 mr-2" />
                @break
            @case('google')
                <x-jet-google-icon class="h-6 w-6 mr-2" />
                @break
            @case('twitter')
                <x-jet-twitter-icon class="h-6 w-6 mr-2" />
                @break
            @case('linkedin')
                <x-jet-linkedin-icon class="h-6 w-6 mr-2" />
                @break
            @case('github')
                <x-jet-github-icon class="h-6 w-6 mr-2" />
                @break
            @case('gitlab')
                <x-jet-gitlab-icon class="h-6 w-6 mr-2" />
                @break
            @case('bitbucket')
                <x-jet-bitbucket-icon class="h-6 w-6 mr-2" />
                @break
            @default
        @endswitch

        <div>
            <div class="text-sm font-semibold text-gray-600">
                {{ ucfirst($provider) }}
            </div>

            <div class="text-xs text-gray-500">
                {{ $createdAt }}
            </div>
        </div>
    </div>

    <div>
        {{ $action }}
    </div>
</div>

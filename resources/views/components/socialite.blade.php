<div class="flex flex-row items-center justify-between py-4 text-gray-500">
    <hr class="w-full mr-2">
        {{ __('Or') }}
    <hr class="w-full ml-2">
</div>

<div class="flex items-center justify-center">
    @if (Laravel\Jetstream\JetStream::hasSocialiteSupportFor('facebook'))
        <a href="{{ route('oauth.redirect', ['provider' => 'facebook']) }}">
            <x-jet-facebook-icon class="h-6 w-6 mx-2" />
        </a>
    @endif

    @if (Laravel\Jetstream\JetStream::hasSocialiteSupportFor('google'))
        <a href="{{ route('oauth.redirect', ['provider' => 'google']) }}" >
            <x-jet-google-icon class="h-6 w-6 mx-2" />
        </a>
    @endif

    @if (Laravel\Jetstream\JetStream::hasSocialiteSupportFor('twitter'))
        <a href="{{ route('oauth.redirect', ['provider' => 'twitter']) }}">
            <x-jet-twitter-icon class="h-6 w-6 mx-2" />
        </a>
    @endif

    @if (Laravel\Jetstream\JetStream::hasSocialiteSupportFor('linkedin'))
        <a href="{{ route('oauth.redirect', ['provider' => 'linkedin']) }}">
            <x-jet-linked-in-icon class="h-6 w-6 mx-2" />
        </a>
    @endif

    @if (Laravel\Jetstream\JetStream::hasSocialiteSupportFor('github'))
        <a href="{{ route('oauth.redirect', ['provider' => 'github']) }}">
            <x-jet-github-icon class="h-6 w-6 mx-2" />
        </a>
    @endif

    @if (Laravel\Jetstream\JetStream::hasSocialiteSupportFor('gitlab'))
        <a href="{{ route('oauth.redirect', ['provider' => 'gitlab']) }}">
            <x-jet-gitlab-icon class="h-6 w-6 mx-2" />
        </a>
    @endif

    @if (Laravel\Jetstream\JetStream::hasSocialiteSupportFor('bitbucket'))
        <a href="{{ route('oauth.redirect', ['provider' => 'bitbucket']) }}">
            <x-jet-bitbucket-icon />
        </a>
    @endif
</div>

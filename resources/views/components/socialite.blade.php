<div class="flex flex-row items-center justify-between py-4 text-gray-500">
    <hr class="w-full mr-2">
        Or
    <hr class="w-full ml-2">
</div>

<div class="flex items-center justify-center">
    <!-- Facebook -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('facebook'))
        <a href="{{ route('socialite.redirect', ['provider' => 'facebook']) }}">
            <x-jet-facebook-icon />
        </a>
    @endif

    <!-- Google -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('google'))
        <a href="{{ route('socialite.redirect', ['provider' => 'google']) }}" >
            <x-jet-google-icon />
        </a>
    @endif

    <!-- Twitter -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('twitter'))
        <a href="{{ route('socialite.redirect', ['provider' => 'twitter']) }}">
            <x-jet-twitter-icon />
        </a>
    @endif

    <!-- LinkedIn -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('linkedin'))
        <a href="{{ route('socialite.redirect', ['provider' => 'linkedin']) }}">
            <x-jet-linkedin-icon />
        </a>
    @endif

    <!-- GitHub -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('github'))
        <a href="{{ route('socialite.redirect', ['provider' => 'github']) }}">
            <x-jet-github-icon />
        </a>
    @endif

    <!-- GitLab -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('gitlab'))
        <a href="{{ route('socialite.redirect', ['provider' => 'gitlab']) }}">
            <x-jet-gitlab-icon />
        </a>
    @endif

    <!-- Bitbucket -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('bitbucket'))
        <a href="{{ route('socialite.redirect', ['provider' => 'bitbucket']) }}">
            <x-jet-bitbucket-icon />
        </a>
    @endif
</div>

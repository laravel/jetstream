<div class="flex flex-row items-center justify-between py-4 text-gray-500">
    <hr class="w-full mr-2">
        Or
    <hr class="w-full ml-2">
</div>

<div class="flex items-center justify-center">
    <!-- Facebook -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('facebook'))
    <x-jet-social-button href="{{ route('socialite.redirect', ['provider' => 'facebook']) }}" color="#1778F2;">
            <svg class="w-6 h-6 mx-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M23.9981 11.9991C23.9981 5.37216 18.626 0 11.9991 0C5.37216 0 0 5.37216 0 11.9991C0 17.9882 4.38789 22.9522 10.1242 23.8524V15.4676H7.07758V11.9991H10.1242V9.35553C10.1242 6.34826 11.9156 4.68714 14.6564 4.68714C15.9692 4.68714 17.3424 4.92149 17.3424 4.92149V7.87439H15.8294C14.3388 7.87439 13.8739 8.79933 13.8739 9.74824V11.9991H17.2018L16.6698 15.4676H13.8739V23.8524C19.6103 22.9522 23.9981 17.9882 23.9981 11.9991Z"/>
            </svg>
        </x-jet-social-button>
    @endif

    <!-- Google -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('google'))
        <x-jet-social-button href="{{ route('socialite.redirect', ['provider' => 'google']) }}" >
            <svg class="w-6 h-6 mx-2" viewBox="0 0 533.5 544.3" xmlns="http://www.w3.org/2000/svg">
                <path d="M533.5 278.4c0-18.5-1.5-37.1-4.7-55.3H272.1v104.8h147c-6.1 33.8-25.7 63.7-54.4 82.7v68h87.7c51.5-47.4 81.1-117.4 81.1-200.2z" fill="#4285f4"/>
                <path d="M272.1 544.3c73.4 0 135.3-24.1 180.4-65.7l-87.7-68c-24.4 16.6-55.9 26-92.6 26-71 0-131.2-47.9-152.8-112.3H28.9v70.1c46.2 91.9 140.3 149.9 243.2 149.9z" fill="#34a853"/>
                <path d="M119.3 324.3c-11.4-33.8-11.4-70.4 0-104.2V150H28.9c-38.6 76.9-38.6 167.5 0 244.4l90.4-70.1z" fill="#fbbc04"/>
                <path d="M272.1 107.7c38.8-.6 76.3 14 104.4 40.8l77.7-77.7C405 24.6 339.7-.8 272.1 0 169.2 0 75.1 58 28.9 150l90.4 70.1c21.5-64.5 81.8-112.4 152.8-112.4z" fill="#ea4335"/>
            </svg>
        </x-jet-social-button>
    @endif

    <!-- Twitter -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('twitter'))
        <x-jet-social-button href="{{ route('socialite.redirect', ['provider' => 'twitter']) }}" color="#00acee">
            <svg class="w-6 h-6 mx-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm6.066 9.645c.183 4.04-2.83 8.544-8.164 8.544-1.622 0-3.131-.476-4.402-1.291 1.524.18 3.045-.244 4.252-1.189-1.256-.023-2.317-.854-2.684-1.995.451.086.895.061 1.298-.049-1.381-.278-2.335-1.522-2.304-2.853.388.215.83.344 1.301.359-1.279-.855-1.641-2.544-.889-3.835 1.416 1.738 3.533 2.881 5.92 3.001-.419-1.796.944-3.527 2.799-3.527.825 0 1.572.349 2.096.907.654-.128 1.27-.368 1.824-.697-.215.671-.67 1.233-1.263 1.589.581-.07 1.135-.224 1.649-.453-.384.578-.87 1.084-1.433 1.489z"/>
            </svg>
        </x-jet-social-button>
    @endif

    <!-- LinkedIn -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('linkedin'))
        <x-jet-social-button href="{{ route('socialite.redirect', ['provider' => 'linkedin']) }}" color="#0e76a8">
            <svg class="w-6 h-6 mx-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2 16h-2v-6h2v6zm-1-6.891c-.607 0-1.1-.496-1.1-1.109 0-.612.492-1.109 1.1-1.109s1.1.497 1.1 1.109c0 .613-.493 1.109-1.1 1.109zm8 6.891h-1.998v-2.861c0-1.881-2.002-1.722-2.002 0v2.861h-2v-6h2v1.093c.872-1.616 4-1.736 4 1.548v3.359z"/>
            </svg>
        </x-jet-social-button>
    @endif

    <!-- GitHub -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('github'))
        <x-jet-social-button href="{{ route('socialite.redirect', ['provider' => 'github']) }}">
            <svg class="w-6 h-6 mx-2" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                <path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/>
            </svg>
        </x-jet-social-button>
    @endif

    <!-- GitLab -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('gitlab'))
        <x-jet-social-button href="{{ route('socialite.redirect', ['provider' => 'gitlab']) }}">
            <svg class="w-6 h-6 mx-2" xmlns="http://www.w3.org/2000/svg" fill-rule="evenodd" viewBox="0 0 24 24">
                <path d="M12,23.1l4.4-13.6H7.6L12,23.1z" fill="#E24329"/>
                <path d="M12,23.1L7.6,9.4H1.4L12,23.1z" fill="#FC6D26"/>
                <path d="M1.4,9.4L0,13.6c-0.1,0.4,0,0.8,0.3,1L12,23.1L1.4,9.4z" fill="#FCA326"/>
                <path d="M1.4,9.4h6.2L4.9,1.3C4.8,0.8,4.2,0.8,4,1.3L1.4,9.4z" fill="#E24329"/>
                <path d="M12,23.1l4.4-13.6h6.2L12,23.1z" fill="#FC6D26"/>
                <path d="M22.6,9.4l1.3,4.1c0.1,0.4,0,0.8-0.3,1L12,23.1L22.6,9.4z" fill="#FCA326"/>
                <path d="M22.6,9.4h-6.2l2.7-8.2c0.1-0.4,0.7-0.4,0.9,0L22.6,9.4z" fill="#E24329"/>
            </svg>
        </x-jet-social-button>
    @endif

    <!-- Bitbucket -->
    @if(\Laravel\Jetstream\JetStream::hasSocialiteSupportFor('bitbucket'))
        <x-jet-social-button href="{{ route('socialite.redirect', ['provider' => 'bitbucket']) }}">
            <svg class="w-6 h-6 mx-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <polygon points="9.5,15.5 14.5,15.5 15.7,8.5 8.2,8.5" fill="none"/>
                <path d="M0.8,1.2C0.4,1.2,0,1.5,0,2c0,0,0,0.1,0,0.1l3.3,19.8c0.1,0.5,0.5,0.9,1,0.9H20c0.4,0,0.7-0.3,0.8-0.6l3.3-20 c0.1-0.4-0.2-0.8-0.6-0.9c0,0-0.1,0-0.1,0L0.8,1.2z M14.5,15.5h-5L8.2,8.5h7.6L14.5,15.5z" fill="#2684FF"/>

                <linearGradient id="grad1" gradientUnits="userSpaceOnUse" x1="24.6235" y1="16.2853" x2="12.6969" y2="6.977" gradientTransform="matrix(1 0 0 -1 0 26.73)">
                    <stop  offset="0.18" style="stop-color:#0052CC"/>
                    <stop  offset="1" style="stop-color:#2684FF"/>
                </linearGradient>
                <path d="M23,8.5h-7.2l-1.2,7.1h-5l-5.9,7c0.2,0.2,0.4,0.3,0.7,0.3H20c0.4,0,0.7-0.3,0.8-0.6L23,8.5z" fill="url(#grad1)"/>
            </svg>
        </x-jet-social-button>
    @endif
</div>

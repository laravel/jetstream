<x-guest-layout>
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        @if (session('status') === 'authorization-approved')
            <div class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
                {{ __('Success! Continue on your device.') }}
            </div>
        @elseif(session('status') === 'authorization-denied')
            <div class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
                {{ __('Denied! Device authorization canceled.') }}
            </div>
        @endif

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            {{ __('Enter the code displayed on your device.') }}
        </div>

        <x-validation-errors class="mb-4" />

        <form method="GET" action="{{ route('passport.device.authorizations.authorize') }}">
            <div class="block">
                <x-label for="user_code" value="{{ __('Code') }}" />
                <x-input id="user_code" class="block mt-1 w-full" type="text" name="user_code" :value="old('user_code')" required autofocus autocomplete="off" autocapitalize="characters" autocorrect="off" spellcheck="false" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <x-button>
                    {{ __('Continue') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>

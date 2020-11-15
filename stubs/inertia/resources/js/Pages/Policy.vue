<template>
    <guest-layout>
        <div class="pt-4 bg-gray-100">
            <div class="min-h-screen flex flex-col items-center pt-6 sm:pt-0">
                <div>
                    <jet-authentication-card-logo />
                </div>

                <div v-html="policy" class="w-full sm:max-w-2xl mt-6 p-6 bg-white shadow-md overflow-hidden sm:rounded-lg prose">
                </div>
            </div>
        </div>
    </guest-layout>
</template>

<script>
import GuestLayout from '@/Layouts/GuestLayout'
import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo'

export default {
    props: ['policy'],

    components: {
        GuestLayout,
        JetAuthenticationCardLogo,
    },
}
</script>

<template>
    <app-layout>
        <template #header>
            <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
                API Tokens
            </h2>
        </template>

        <div>
            <div class="py-10 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <api-token-manager :tokens="tokens"
                                   :available-permissions="availablePermissions"
                                   :default-permissions="defaultPermissions" />
            </div>
        </div>
    </app-layout>
</template>

<script>
    import ApiTokenManager from './ApiTokenManager'
    import AppLayout from '@/Layouts/AppLayout'

    export default {
        props: [
            'tokens',
            'availablePermissions',
            'defaultPermissions',
        ],

        components: {
            ApiTokenManager,
            AppLayout,
        },
    }
</script>

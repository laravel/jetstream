<script setup>
import { ref } from 'vue';
import { Head } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import TwoFactorChallanageForm from '@/Pages/Auth/Partials/TwoFactorChallanageForm.vue'

const recovery = ref(false);

const toggleRecovery = async () => {
    recovery.value ^= true;
};
</script>

<template>
    <Head title="Two-factor Confirmation" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo @toggle="toggleRecovery" />
        </template>

        <TwoFactorChallanageForm
            v-if="!recovery"
            @toggle="toggleRecovery"
            index="code"
            label="Code"
        >
            <template #description>
                Please confirm access to your account by entering the authentication code provided by your authenticator application.
            </template>
            <template #toggle>
                Use a recovery code
            </template>
        </TwoFactorChallanageForm>

        <TwoFactorChallanageForm
            v-else
            @toggle="toggleRecovery"
            index="recovery_code"
            label="Recovery Code"
        >
            <template #description>
                Please confirm access to your account by entering one of your emergency recovery codes.
            </template>
            <template #toggle>
                Use an authentication code
            </template>
        </TwoFactorChallanageForm>
    </AuthenticationCard>
</template>

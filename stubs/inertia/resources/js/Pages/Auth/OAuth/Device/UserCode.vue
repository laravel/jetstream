<script setup>
import { computed } from 'vue';
import { Head, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

defineProps({
    status: String,
});

const form = useForm({
    user_code: '',
});

const submit = () => {
    form.get(route('passport.device.authorizations.authorize'));
};

const authorizationApproved = computed(() => props.status === 'authorization-approved');
const authorizationDenied = computed(() => props.status === 'authorization-denied');
</script>

<template>
    <Head title="Connect a Device" />

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo />
        </template>

        <div v-if="authorizationApproved" class="mb-4 font-medium text-sm text-green-600 dark:text-green-400">
            Success! Continue on your device.
        </div>
        <div v-else-if="authorizationDenied" class="mb-4 font-medium text-sm text-red-600 dark:text-red-400">
            Denied! Device authorization canceled.
        </div>

        <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
            Enter the code displayed on your device.
        </div>

        <form @submit.prevent="submit">
            <div>
                <InputLabel for="user_code" value="Code" />
                <TextInput
                    id="user_code"
                    v-model="form.user_code"
                    type="text"
                    class="mt-1 block w-full"
                    required
                    autofocus
                    autocomplete="off"
                    autocapitalize="characters"
                    autocorrect="off"
                    spellcheck="false"
                />
                <InputError class="mt-2" :message="form.errors.user_code" />
            </div>

            <div class="flex items-center justify-end mt-4">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Continue
                </PrimaryButton>
            </div>
        </form>
    </AuthenticationCard>
</template>

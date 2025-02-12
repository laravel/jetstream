<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3';
import AuthenticationCard from '@/Components/AuthenticationCard.vue';
import AuthenticationCardLogo from '@/Components/AuthenticationCardLogo.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    user: Object,
    client: Object,
    scopes: Array,
    authToken: String,
});

const form = useForm({
    state: route().params.state,
    client_id: props.client.id,
    auth_token: props.authToken,
});

const approve = () => {
    form.post(route('passport.authorizations.approve'));
};
const deny = () => {
    form.transform(data => ({
        ...data,
        _method: 'delete',
    })).post(route('passport.authorizations.deny'));
};
</script>

<template>
    <Head title="Authorization Request"/>

    <AuthenticationCard>
        <template #logo>
            <AuthenticationCardLogo/>
        </template>

        <div class="mb-4 text-gray-600 text-center">
            <p><strong>{{ user.name }}</strong></p>
            <p class="text-sm">{{ user.email }}</p>
        </div>

        <div class="mb-4 text-sm text-gray-600">
            <strong>{{ client.name }}</strong> is requesting permission to access your account.
        </div>

        <div v-if="scopes.length" class="mb-4 text-sm text-gray-600">
            <p class="pb-1">This application will be able to:</p>

            <ul class="list-inside list-disc">
                <li v-for="scope in scopes">{{ scope.description }}</li>
            </ul>
        </div>

        <div class="flex flex-row-reverse gap-3 mt-4 flex-wrap items-center">
            <form @submit.prevent="approve">
                <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Authorize
                </PrimaryButton>
            </form>

            <form @submit.prevent="deny">
                <SecondaryButton type="submit" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Decline
                </SecondaryButton>
            </form>

            <Link :href="route('passport.authorizations.authorize', {...route().params, prompt: 'login'})"
                  class="underline text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 dark:hover:text-gray-100 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 dark:focus:ring-offset-gray-800">
                Log into another account
            </Link>
        </div>
    </AuthenticationCard>
</template>

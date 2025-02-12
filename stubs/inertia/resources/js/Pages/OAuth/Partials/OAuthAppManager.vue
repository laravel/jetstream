<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import ActionSection from '@/Components/ActionSection.vue';
import Checkbox from '@/Components/Checkbox.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    apps: Array,
});

const createOAuthAppForm = useForm({
    name: '',
    redirect_uris: [],
    confidential: false,
});

const updateOAuthAppForm = useForm({
    name: '',
    redirect_uris: [],
});

const deleteOAuthAppForm = useForm({});

const displayingClientCredentials = ref(false);
const oauthAppBeingManaged = ref(null);
const oauthAppBeingDeleted = ref(null);

const createOAuthApp = () => {
    createOAuthAppForm.post(route('oauth-apps.store'), {
        preserveScroll: true,
        onSuccess: () => {
            displayingClientCredentials.value = true;
            createOAuthAppForm.reset();
        },
    });
};

const manageOAuthApp = (app) => {
    updateOAuthAppForm.name = app.name;
    updateOAuthAppForm.redirect_uris = app.redirect_uris;
    oauthAppBeingManaged.value = app;
};

const updateOAuthApp = () => {
    updateOAuthAppForm.put(route('oauth-apps.update', oauthAppBeingManaged.value), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (oauthAppBeingManaged.value = null),
    });
};

const confirmOAuthAppDeletion = (app) => {
    oauthAppBeingDeleted.value = app;
};

const deleteOAuthApp = () => {
    deleteOAuthAppForm.delete(route('oauth-apps.destroy', oauthAppBeingDeleted.value), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (oauthAppBeingDeleted.value = null),
    });
};
</script>

<template>
    <div>
        <!-- Register OAuth App -->
        <FormSection @submitted="createOAuthApp">
            <template #title>
                Register OAuth App
            </template>

            <template #description>
                You may register an OAuth client to use our application's API.
            </template>

            <template #form>
                <!-- App Name -->
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel for="name" value="Application Name" />
                    <TextInput
                        id="name"
                        v-model="createOAuthAppForm.name"
                        type="text"
                        class="mt-1 block w-full"
                        autofocus
                        autocomplete="off"
                    />
                    <InputError :message="createOAuthAppForm.errors.name" class="mt-2" />
                </div>

                <!-- Authorization Redirect URI -->
                <div class="col-span-6 sm:col-span-4">
                    <InputLabel for="redirect_uri" value="Authorization Redirect URI" />
                    <TextInput
                        id="redirect_uri"
                        v-model="createOAuthAppForm.redirect_uris[0]"
                        type="url"
                        class="mt-1 block w-full"
                        autocomplete="off"
                    />
                    <InputError :message="createOAuthAppForm.errors.redirect_uris" class="mt-2" />
                </div>

                <!-- Confidential -->
                <div class="col-span-6 sm:col-span-4">
                    <label for="confidential" class="flex items-center">
                        <Checkbox id="confidential" v-model:checked="createOAuthAppForm.confidential" />
                        <span class="ms-2 text-sm text-gray-600 dark:text-gray-400">Confidential</span>
                    </label>
                    <InputError :message="createOAuthAppForm.errors.confidential" class="mt-2" />
                </div>
            </template>

            <template #actions>
                <ActionMessage :on="createOAuthAppForm.recentlySuccessful" class="me-3">
                    Registered.
                </ActionMessage>

                <PrimaryButton :class="{ 'opacity-25': createOAuthAppForm.processing }" :disabled="createOAuthAppForm.processing">
                    Register
                </PrimaryButton>
            </template>
        </FormSection>

        <div v-if="apps.length > 0">
            <SectionBorder />

            <!-- Manage OAuth Apps -->
            <div class="mt-10 sm:mt-0">
                <ActionSection>
                    <template #title>
                        Manage OAuth Apps
                    </template>

                    <template #description>
                        You may delete any of your existing registered apps if they are no longer needed.
                    </template>

                    <!-- OAuth App List -->
                    <template #content>
                        <div class="space-y-6">
                            <div v-for="app in apps" :key="app.id" class="flex items-center justify-between">
                                <div class="dark:text-white">
                                    {{ app.name }}
                                    <span class="text-sm italic text-gray-400">
                                        &ndash; {{ app.is_confidential ? 'Confidential' : 'Public' }}
                                    </span>
                                </div>

                                <div class="flex items-center ms-2">
                                    <div class="text-sm text-gray-400">
                                        Created at {{ app.created_date }}
                                    </div>

                                    <button
                                        class="cursor-pointer ms-6 text-sm text-gray-400 underline"
                                        @click="manageOAuthApp(app)"
                                    >
                                        Manage
                                    </button>

                                    <button class="cursor-pointer ms-6 text-sm text-red-500" @click="confirmOAuthAppDeletion(app)">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </ActionSection>
            </div>
        </div>

        <!-- Client Credentials Modal -->
        <DialogModal :show="displayingClientCredentials" @close="displayingClientCredentials = false">
            <template #title>
                Client Credentials
            </template>

            <template #content>
                <div class="grid grid-cols-1 gap-4">
                    <div>
                        Please copy your new client credentials.

                        <template v-if="$page.props.jetstream.flash.client_secret">
                            For your security, client secret won't be shown again.
                        </template>
                    </div>

                    <div>
                        <div class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                            Client ID
                        </div>
                        <div v-if="$page.props.jetstream.flash.client_id" class="mt-1 bg-gray-100 dark:bg-gray-900 px-4 py-2 rounded font-mono text-sm text-gray-500 break-all">
                            {{ $page.props.jetstream.flash.client_id }}
                        </div>
                    </div>

                    <div v-if="$page.props.jetstream.flash.client_secret">
                        <div class="block font-medium text-sm text-gray-700 dark:text-gray-300">
                            Client Secret
                        </div>
                        <div class="mt-1 bg-gray-100 dark:bg-gray-900 px-4 py-2 rounded font-mono text-sm text-gray-500 break-all">
                            {{ $page.props.jetstream.flash.client_secret }}
                        </div>
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="displayingClientCredentials = false">
                    Close
                </SecondaryButton>
            </template>
        </DialogModal>

        <!-- OAuth App Management Modal -->
        <DialogModal :show="oauthAppBeingManaged != null" @close="oauthAppBeingManaged = null">
            <template #title>
                OAuth App Management
            </template>

            <template #content>
                <div class="grid grid-cols-1 gap-4">
                    <!-- Client ID -->
                    <div>
                        <InputLabel for="manage_client_id" value="Client ID" />
                        <TextInput
                            id="manage_client_id"
                            :value="oauthAppBeingManaged?.id"
                            type="text"
                            class="mt-1 block w-full bg-gray-100 font-mono text-gray-500 break-all"
                            readonly
                        />
                    </div>

                    <!-- App Name -->
                    <div>
                        <InputLabel for="manage_name" value="Application Name" />
                        <TextInput
                            id="manage_name"
                            v-model="updateOAuthAppForm.name"
                            type="text"
                            class="mt-1 block w-full"
                            autocomplete="off"
                        />
                        <InputError :message="updateOAuthAppForm.errors.name" class="mt-2" />
                    </div>

                    <!-- Authorization Redirect URI -->
                    <div>
                        <InputLabel for="manage_redirect_uri" value="Authorization Redirect URI" />
                        <TextInput
                            id="manage_redirect_uri"
                            v-model="updateOAuthAppForm.redirect_uris[0]"
                            type="url"
                            class="mt-1 block w-full"
                            autocomplete="off"
                        />
                        <InputError :message="updateOAuthAppForm.errors.redirect_uris" class="mt-2" />
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="oauthAppBeingManaged = null">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ms-3"
                    :class="{ 'opacity-25': updateOAuthAppForm.processing }"
                    :disabled="updateOAuthAppForm.processing"
                    @click="updateOAuthApp"
                >
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>

        <!-- Delete OAuth App Confirmation Modal -->
        <ConfirmationModal :show="oauthAppBeingDeleted != null" @close="oauthAppBeingDeleted = null">
            <template #title>
                Delete OAuth App
            </template>

            <template #content>
                Are you sure you would like to delete this app?
            </template>

            <template #footer>
                <SecondaryButton @click="oauthAppBeingDeleted = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteOAuthAppForm.processing }"
                    :disabled="deleteOAuthAppForm.processing"
                    @click="deleteOAuthApp"
                >
                    Delete
                </DangerButton>
            </template>
        </ConfirmationModal>
    </div>
</template>

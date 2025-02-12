<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SectionBorder from '@/Components/SectionBorder.vue';

const props = defineProps({
    connections: Array,
});

const deleteConnectionForm = useForm({});

const connectionClientBeingDeleted = ref(null);

const confirmConnectionDeletion = (client) => {
    connectionClientBeingDeleted.value = client;
};

const deleteConnection = () => {
    deleteConnectionForm.delete(route('oauth-connections.destroy', connectionClientBeingDeleted.value), {
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => (connectionClientBeingDeleted.value = null),
    });
};
</script>

<template>
    <div>
        <div v-if="connections.length > 0">
            <!-- Manage OAuth Connections -->
            <div class="mt-10 sm:mt-0">
                <ActionSection>
                    <template #title>
                        Manage Authorized Apps
                    </template>

                    <template #description>
                        Keep track of your connections with third-party apps and services. You may delete the access you've given to any of your existing authorized apps if they are no longer needed.
                    </template>

                    <!-- OAuth Connection List -->
                    <template #content>
                        <div class="space-y-6">
                            <div v-for="connection in connections" :key="connection.client.id" class="flex items-center justify-between">
                                <div>
                                    <div>
                                        {{ connection.client.name }}
                                    </div>
                                    <div class="text-sm italic text-gray-500">
                                        {{ connection.scopes.join(', ') }}
                                    </div>
                                </div>

                                <div class="flex items-center ms-2">
                                    <div class="text-sm text-gray-400">
                                        {{ connection.tokens_count }} Tokens
                                    </div>

                                    <button class="cursor-pointer ms-6 text-sm text-red-500" @click="confirmConnectionDeletion(connection.client)">
                                        Delete
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </ActionSection>
            </div>

            <SectionBorder />
        </div>

        <!-- Delete OAuth Connection Confirmation Modal -->
        <ConfirmationModal :show="connectionClientBeingDeleted != null" @close="connectionClientBeingDeleted = null">
            <template #title>
                Delete OAuth Connection
            </template>

            <template #content>
                Are you sure you would like to delete all connections you have with this app?
            </template>

            <template #footer>
                <SecondaryButton @click="connectionClientBeingDeleted = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ms-3"
                    :class="{ 'opacity-25': deleteConnectionForm.processing }"
                    :disabled="deleteConnectionForm.processing"
                    @click="deleteConnection"
                >
                    Delete
                </DangerButton>
            </template>
        </ConfirmationModal>
    </div>
</template>

<template>
    <jet-action-section>
        <template #title>
            Connected Accounts
        </template>

        <template #description>
            Manage and remove your connect accounts.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                You may disconnect any of your connected accounts below. If you feel any of your connected accounts have
                been compromised, you should disconnect them immediately.
            </div>

            <div class="mt-5 space-y-6 grid grid-cols-3" v-if="providers.length > 0">
                <div class="p-3 flex items-center justify-between col-span-1" v-for="provider in providers">
                    <!-- Provider Info -->
                    <div>
                        <!-- Provider SVG Logo -->
                        <div>

                        </div>

                        <!-- Provider details -->
                        <div>
                            <div class="text-sm font-semibold text-gray-600">
                                {{ session.provider_name }}
                            </div>

                            <div>
                                <div class="text-xs text-gray-500">
                                    Added on {{ session.created_at }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <jet-button @click="confirmRemove(provider.id)" loading.attr="disabled" class="bg-red-600 hover:bg-red-500" />
                </div>
            </div>

            <!-- Confirmation Modal -->
            <jet-dialog-modal :show="confirmingRemove" @close="confirmingRemove = false">
                <template #title>
                    Remove Connected Account
                </template>

                <template #content>
                    Please enter your password to confirm you would like to logout of your other browser sessions across
                    all of your devices.

                    <div class="mt-4">
                        <jet-input type="password" class="mt-1 block w-3/4" placeholder="Password"
                                   ref="password"
                                   v-model="form.password"
                                   @keyup.enter.native="logoutOtherBrowserSessions"/>

                        <jet-input-error :message="form.error('password')" class="mt-2"/>
                    </div>
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="confirmingRemove = false">
                        Nevermind
                    </jet-secondary-button>

                    <jet-button class="ml-2 bg-red-600 hover:bg-red-500" @click.native="removeConnectedAccount(providerId)"
                                :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                        Remove Connected Account
                    </jet-button>
                </template>
            </jet-dialog-modal>
        </template>
    </jet-action-section>
</template>

<script>
import JetActionMessage from './../../Jetstream/ActionMessage';
import JetActionSection from './../../Jetstream/ActionSection';
import JetButton from './../../Jetstream/Button';
import JetDialogModal from './../../Jetstream/DialogModal';
import JetInput from './../../Jetstream/Input';
import JetInputError from './../../Jetstream/InputError';
import JetSecondaryButton from './../../Jetstream/SecondaryButton';

export default {
    props: ['providers'],

    components: {
        JetActionMessage,
        JetActionSection,
        JetButton,
        JetDialogModal,
        JetInput,
        JetInputError,
        JetSecondaryButton,
    },

    data() {
        return {
            confirmingRemove: false,
            providerId: null,

            form: this.$inertia.form({
                '_method': 'DELETE',
                password: '',
            }, {
                bag: 'removeConnectedAccount'
            })
        };
    },

    methods: {
        confirmRemove(id) {
            this.form.password = '';

            this.providerId = id;

            this.confirmingRemove = true;

            setTimeout(() => {
                this.$refs.password.focus();
            }, 250);
        },

        removeConnectedAccount(id) {
            this.form.post(`/user/remove-connected-account/${id}`, {
                preserveScroll: true
            }).then(response => {
                if (!this.form.hasErrors()) {
                    this.confirmingRemove = false;
                }
            });
        },
    },
};
</script>

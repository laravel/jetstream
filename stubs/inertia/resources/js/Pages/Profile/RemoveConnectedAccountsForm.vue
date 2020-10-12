<template>
    <jet-action-section>
        <template #title>
            Connected Accounts
        </template>

        <template #description>
            Manage and remove your connect accounts.
        </template>

        <template #content>
            <h3 class="text-lg font-medium text-gray-900" v-if="accounts.length === 0">
                You have no connected accounts.
            </h3>
            <h3 class="text-lg font-medium text-gray-900" v-else>
                Your connected accounts.
            </h3>

            <div class="mt-3 ax-w-xl text-sm text-gray-600" v-if="accounts.length === 0">
                When you have one or more connected accounts, they will appear below. You may disconnect any of your
                connected accounts at any time. If you feel any of your connected accounts have been compromised, you
                should disconnect them immediately and change your password.
            </div>
            <div class="mt-3 ax-w-xl text-sm text-gray-600" v-else>
                You may disconnect any of your connected accounts below at any time. If you feel any of your connected
                accounts have been compromised, you should disconnect them immediately and change your password.
            </div>

            <div class="mt-5 space-y-6 grid grid-cols-3" v-if="accounts.length > 0">
                <div class="p-3 flex items-center justify-between col-span-1" v-for="account in accounts">
                    <!-- Provider Info -->
                    <div>
                        <!-- Provider SVG Logo -->
                        <div>

                        </div>

                        <!-- Provider details -->
                        <div>
                            <div class="text-sm font-semibold text-gray-600">
                                {{ account.provider_name }}
                            </div>

                            <div>
                                <div class="text-xs text-gray-500">
                                    Added on {{ account.created_at }}
                                </div>
                            </div>
                        </div>
                    </div>
                    <jet-button @click="confirmRemove(account.id)" loading.attr="disabled"
                                class="bg-red-600 hover:bg-red-500"/>
                </div>
            </div>

            <!-- Confirmation Modal -->
            <jet-dialog-modal :show="confirmingRemove" @close="confirmingRemove = false">
                <template #title>
                    Remove Connected Account
                </template>

                <template #content>
                    Please enter your password to confirm you would like to remove this connected account.

                    <div class="mt-4">
                        <jet-input type="password" class="mt-1 block w-3/4" placeholder="Password"
                                   ref="password"
                                   v-model="form.password"
                                   @keyup.enter.native="removeConnectedAccount(accountId)"/>

                        <jet-input-error :message="form.error('password')" class="mt-2"/>
                    </div>
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="confirmingRemove = false">
                        Nevermind
                    </jet-secondary-button>

                    <jet-button class="ml-2 bg-red-600 hover:bg-red-500"
                                @click.native="removeConnectedAccount(accountId)"
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
    props: ['accounts'],

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
            accountId: null,

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

            this.accountId = id;

            this.confirmingRemove = true;

            setTimeout(() => {
                this.$refs.password.focus();
            }, 250);
        },

        removeConnectedAccount(id) {
            this.form.post(`/user/connected-account/${ id }`, {
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

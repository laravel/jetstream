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

            <div class="mt-5 space-y-6" v-if="accounts.length > 0">
                <div class="p-3 flex items-center justify-between " v-for="account in accounts" :key="account.id">
                    <div class="flex items-center">

                        <jet-facebook-icon class="mr-4" v-if="account.provider_name === 'facebook'"/>
                        <jet-google-icon class="mr-4" v-if="account.provider_name === 'google'"/>
                        <jet-twitter-icon class="mr-4" v-if="account.provider_name === 'twitter'"/>
                        <jet-linkedin-icon class="mr-4" v-if="account.provider_name === 'linkedin'"/>
                        <jet-github-icon class="mr-4" v-if="account.provider_name === 'github'"/>
                        <jet-git-lab-icon class="mr-4" v-if="account.provider_name === 'gitlab'"/>
                        <jet-bitbucket-icon class="mr-4" v-if="account.provider_name === 'bitbucket'"/>

                        <div>
                            <div class="text-sm font-semibold text-gray-600">
                                {{ account.provider_name.charAt(0).toUpperCase() + account.provider_name.slice(1)  }}
                            </div>

                            <div class="text-xs text-gray-500">
                                Added on {{ account.created_at }}
                            </div>
                        </div>
                    </div>
                    <jet-button @click.native="confirmRemove(account.id)" v-if="accounts.length > 1">
                        Remove
                    </jet-button>
                </div>
            </div>

            <!-- Confirmation Modal -->
            <jet-dialog-modal :show="confirmingRemove" @close="confirmingRemove = false">
                <template #title>
                    Remove Connected Account
                </template>

                <template #content>
                    This action cannot be undone.
                </template>

                <template #footer>
                    <jet-secondary-button @click.native="confirmingRemove = false">
                        Nevermind
                    </jet-secondary-button>

                    <jet-button class="ml-2"
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
import JetFacebookIcon from '@/Jetstream/Socialite/FacebookIcon';
import JetGoogleIcon from '@/Jetstream/Socialite/GoogleIcon';
import JetTwitterIcon from '@/Jetstream/Socialite/TwitterIcon';
import JetLinkedinIcon from '@/Jetstream/Socialite/LinkedinIcon';
import JetGithubIcon from '@/Jetstream/Socialite/GithubIcon';
import JetGitLabIcon from '@/Jetstream/Socialite/GitLabIcon';
import JetBitbucketIcon from '@/Jetstream/Socialite/BitbucketIcon';

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
        JetFacebookIcon,
        JetGoogleIcon,
        JetTwitterIcon,
        JetLinkedinIcon,
        JetGithubIcon,
        JetGitLabIcon,
        JetBitbucketIcon
    },

    data() {
        return {
            confirmingRemove: false,
            accountId: null,

            form: this.$inertia.form({
                '_method': 'DELETE',
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

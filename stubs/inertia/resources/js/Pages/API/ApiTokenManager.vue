<template>
    <div>
        <!-- Generate API Token -->
        <jet-form-section @submitted="createApiToken">
            <template #title>
                {{ $t('Create API Token') }}
            </template>

            <template #description>
                {{ $t('API tokens allow third-party services to authenticate with our application on your behalf.') }}
            </template>

            <template #form>
                <!-- Token Name -->
                <div class="col-span-6 sm:col-span-4">
                    <jet-label for="name" :value="$t('Name')" />
                    <jet-input id="name" type="text" class="mt-1 block w-full" v-model="createApiTokenForm.name" autofocus />
                    <jet-input-error :message="createApiTokenForm.error('name')" class="mt-2" />
                </div>

                <!-- Token Permissions -->
                <div class="col-span-6" v-if="availablePermissions.length > 0">
                    <jet-label for="permissions" :value="$t('Permissions')" />

                    <div class="mt-2 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div v-for="permission in availablePermissions" :key="permission">
                            <label class="flex items-center">
                                <jet-checkbox :value="permission" v-model="createApiTokenForm.permissions"/>
                                <span class="ml-2 text-sm text-gray-600">{{ permission }}</span>
                            </label>
                        </div>
                    </div>
                </div>
            </template>

            <template #actions>
                <jet-action-message :on="createApiTokenForm.recentlySuccessful" class="mr-3">
                    {{ $t('Created.') }}
                </jet-action-message>

                <jet-button :class="{ 'opacity-25': createApiTokenForm.processing }" :disabled="createApiTokenForm.processing">
                    {{ $t('Create') }}
                </jet-button>
            </template>
        </jet-form-section>

        <div v-if="tokens.length > 0">
            <jet-section-border />

            <!-- Manage API Tokens -->
            <div class="mt-10 sm:mt-0">
                <jet-action-section>
                    <template #title>
                        {{ $t('Manage API Tokens') }}
                    </template>

                    <template #description>
                        {{ $t('You may delete any of your existing tokens if they are no longer needed.') }}
                    </template>

                    <!-- API Token List -->
                    <template #content>
                        <div class="space-y-6">
                            <div class="flex items-center justify-between" v-for="token in tokens" :key="token.id">
                                <div>
                                    {{ token.name }}
                                </div>

                                <div class="flex items-center">
                                    <div class="text-sm text-gray-400" v-if="token.last_used_at">
                                        {{ $t('Last used {last_used}', { 'last_used': fromNow(token.last_used_at) }) }}
                                    </div>

                                    <button class="cursor-pointer ml-6 text-sm text-gray-400 underline"
                                                @click="manageApiTokenPermissions(token)"
                                                v-if="availablePermissions.length > 0">
                                        {{ $t('Permissions') }}
                                    </button>

                                    <button class="cursor-pointer ml-6 text-sm text-red-500" @click="confirmApiTokenDeletion(token)">
                                        {{ $t('Delete') }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </template>
                </jet-action-section>
            </div>
        </div>

        <!-- Token Value Modal -->
        <jet-dialog-modal :show="displayingToken" @close="displayingToken = false">
            <template #title>
                {{ $t('API Token') }}
            </template>

            <template #content>
                <div>
                    {{ $t('Please copy your new API token. For your security, it won\'t be shown again.') }}
                </div>

                <div class="mt-4 bg-gray-100 px-4 py-2 rounded font-mono text-sm text-gray-500" v-if="$page.props.jetstream.flash.token">
                    {{ $page.props.jetstream.flash.token }}
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="displayingToken = false">
                    {{ $t('Close') }}
                </jet-secondary-button>
            </template>
        </jet-dialog-modal>

        <!-- API Token Permissions Modal -->
        <jet-dialog-modal :show="managingPermissionsFor" @close="managingPermissionsFor = null">
            <template #title>
                {{ $t('API Token Permissions') }}
            </template>

            <template #content>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div v-for="permission in availablePermissions" :key="permission">
                        <label class="flex items-center">
                            <jet-checkbox :value="permission" v-model="updateApiTokenForm.permissions"/>
                            <span class="ml-2 text-sm text-gray-600">{{ permission }}</span>
                        </label>
                    </div>
                </div>
            </template>

            <template #footer>
                <jet-secondary-button @click.native="managingPermissionsFor = null">
                    {{ $t('Nevermind') }}
                </jet-secondary-button>

                <jet-button class="ml-2" @click.native="updateApiToken" :class="{ 'opacity-25': updateApiTokenForm.processing }" :disabled="updateApiTokenForm.processing">
                    {{ $t('Save') }}
                </jet-button>
            </template>
        </jet-dialog-modal>

        <!-- Delete Token Confirmation Modal -->
        <jet-confirmation-modal :show="apiTokenBeingDeleted" @close="apiTokenBeingDeleted = null">
            <template #title>
                {{ $t('Delete API Token') }}
            </template>

            <template #content>
                {{ $t('Are you sure you would like to delete this API token?') }}
            </template>

            <template #footer>
                <jet-secondary-button @click.native="apiTokenBeingDeleted = null">
                    {{ $t('Nevermind') }}
                </jet-secondary-button>

                <jet-danger-button class="ml-2" @click.native="deleteApiToken" :class="{ 'opacity-25': deleteApiTokenForm.processing }" :disabled="deleteApiTokenForm.processing">
                    {{ $t('Delete') }}
                </jet-danger-button>
            </template>
        </jet-confirmation-modal>
    </div>
</template>

<script>
    import JetActionMessage from '@/Jetstream/ActionMessage'
    import JetActionSection from '@/Jetstream/ActionSection'
    import JetButton from '@/Jetstream/Button'
    import JetConfirmationModal from '@/Jetstream/ConfirmationModal'
    import JetDangerButton from '@/Jetstream/DangerButton'
    import JetDialogModal from '@/Jetstream/DialogModal'
    import JetFormSection from '@/Jetstream/FormSection'
    import JetInput from '@/Jetstream/Input'
    import JetCheckbox from '@/Jetstream/Checkbox'
    import JetInputError from '@/Jetstream/InputError'
    import JetLabel from '@/Jetstream/Label'
    import JetSecondaryButton from '@/Jetstream/SecondaryButton'
    import JetSectionBorder from '@/Jetstream/SectionBorder'

    export default {
        components: {
            JetActionMessage,
            JetActionSection,
            JetButton,
            JetConfirmationModal,
            JetDangerButton,
            JetDialogModal,
            JetFormSection,
            JetInput,
            JetCheckbox,
            JetInputError,
            JetLabel,
            JetSecondaryButton,
            JetSectionBorder,
        },

        props: [
            'tokens',
            'availablePermissions',
            'defaultPermissions',
        ],

        data() {
            return {
                createApiTokenForm: this.$inertia.form({
                    name: '',
                    permissions: this.defaultPermissions,
                }, {
                    bag: 'createApiToken',
                    resetOnSuccess: true,
                }),

                updateApiTokenForm: this.$inertia.form({
                    permissions: []
                }, {
                    resetOnSuccess: false,
                    bag: 'updateApiToken',
                }),

                deleteApiTokenForm: this.$inertia.form(),

                displayingToken: false,
                managingPermissionsFor: null,
                apiTokenBeingDeleted: null,
            }
        },

        methods: {
            createApiToken() {
                this.createApiTokenForm.post(route('api-tokens.store'), {
                    preserveScroll: true,
                    onSuccess: () => {
                        if (! this.createApiTokenForm.hasErrors()) {
                            this.displayingToken = true
                        }
                    }
                })
            },

            manageApiTokenPermissions(token) {
                this.updateApiTokenForm.permissions = token.abilities

                this.managingPermissionsFor = token
            },

            updateApiToken() {
                this.updateApiTokenForm.put(route('api-tokens.update', this.managingPermissionsFor), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        this.managingPermissionsFor = null
                    }
                })
            },

            confirmApiTokenDeletion(token) {
                this.apiTokenBeingDeleted = token
            },

            deleteApiToken() {
                this.deleteApiTokenForm.delete(route('api-tokens.destroy', this.apiTokenBeingDeleted), {
                    preserveScroll: true,
                    preserveState: true,
                    onSuccess: () => {
                        this.apiTokenBeingDeleted = null
                    }
                })
            },

            fromNow(timestamp) {
                return moment(timestamp).local().fromNow()
            },
        },
    }
</script>

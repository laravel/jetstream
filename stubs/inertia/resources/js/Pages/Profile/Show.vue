<template>
    <app-layout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ $t('Profile') }}
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                 <div v-if="$page.props.jetstream.canUpdateProfileInformation">
                    <update-profile-information-form :user="$page.props.user" :email-disabled="connectedAccounts.length > 0"/>

                    <jet-section-border />
                </div>

                <div v-if="$page.props.jetstream.hasSocialiteFeatures">
                    <div v-if="$page.props.user.password != null">
                        <div v-if="$page.props.jetstream.canUpdatePassword">
                            <update-password-form class="mt-10 sm:mt-0" />

                            <jet-section-border />
                        </div>

                        <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                            <two-factor-authentication-form class="mt-10 sm:mt-0" />

                            <jet-section-border />
                        </div>
                    </div>

                    <div v-else>
                        <set-password-form class="mt-10 sm:mt-0" />

                        <jet-section-border />
                    </div>

                    <connected-accounts-form :accounts="connectedAccounts" :providers="socialiteProviders" class="mt-10 sm:mt-0" />

                    <jet-section-border />
                </div>

                <div v-else>
                    <div v-if="$page.props.jetstream.canUpdatePassword">
                        <update-password-form class="mt-10 sm:mt-0" />

                        <jet-section-border />
                    </div>

                    <div v-if="$page.props.jetstream.canManageTwoFactorAuthentication">
                        <two-factor-authentication-form class="mt-10 sm:mt-0" />

                        <jet-section-border />
                    </div>
                </div>

                <logout-other-browser-sessions-form :sessions="sessions" class="mt-10 sm:mt-0" />

                <template v-if="$page.props.jetstream.hasAccountDeletionFeatures">
                    <jet-section-border />

                    <delete-user-form class="mt-10 sm:mt-0" />
                </template>
            </div>
        </div>
    </app-layout>
</template>

<script>
    import AppLayout from '@/Layouts/AppLayout'
    import DeleteUserForm from './DeleteUserForm'
    import JetSectionBorder from '@/Jetstream/SectionBorder'
    import LogoutOtherBrowserSessionsForm from './LogoutOtherBrowserSessionsForm'
    import TwoFactorAuthenticationForm from './TwoFactorAuthenticationForm'
    import SetPasswordForm from './SetPasswordForm'
    import UpdatePasswordForm from './UpdatePasswordForm'
    import UpdateProfileInformationForm from './UpdateProfileInformationForm'
    import ConnectedAccountsForm from './ConnectedAccountsForm';

    export default {
        props: ['sessions', 'connectedAccounts', 'socialiteProviders'],

        components: {
            ConnectedAccountsForm,
            AppLayout,
            DeleteUserForm,
            JetSectionBorder,
            LogoutOtherBrowserSessionsForm,
            TwoFactorAuthenticationForm,
            SetPasswordForm,
            UpdatePasswordForm,
            UpdateProfileInformationForm,
        },
    }
</script>

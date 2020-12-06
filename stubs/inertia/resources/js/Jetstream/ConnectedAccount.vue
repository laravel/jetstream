<template>
    <div class="px-3 flex items-center justify-between">
        <div class="flex items-center">

            <jet-facebook-icon class="h-6 w-6 mr-2" v-if="provider === 'facebook'"/>
            <jet-google-icon class="h-6 w-6 mr-2" v-if="provider === 'google'"/>
            <jet-twitter-icon class="h-6 w-6 mr-2" v-if="provider === 'twitter'"/>
            <jet-linked-in-icon class="h-6 w-6 mr-2" v-if="provider === 'linkedin'"/>
            <jet-github-icon class="h-6 w-6 mr-2" v-if="provider === 'github'"/>
            <jet-git-lab-icon class="h-6 w-6 mr-2" v-if="provider === 'gitlab'"/>
            <jet-bitbucket-icon class="h-6 w-6 mr-2" v-if="provider === 'bitbucket'"/>

            <div>
                <div class="text-sm font-semibold text-gray-600">
                    {{ provider.charAt(0).toUpperCase() + provider.slice(1)  }}
                </div>

                <div v-if="createdAt !== null" class="text-xs text-gray-500">
                    {{ $t('Added on {added_on}.', { 'added_on': createdAt }) }}
                </div>

                <div v-else class="text-xs text-gray-500">
                    {{ $t('Not connected.') }}
                </div>
            </div>
        </div>

        <slot name="action"></slot>
    </div>
</template>

<script>
    import JetFacebookIcon from '@/Socialite/FacebookIcon';
    import JetGoogleIcon from '@/Socialite/GoogleIcon';
    import JetTwitterIcon from '@/Socialite/TwitterIcon';
    import JetLinkedInIcon from '@/Socialite/LinkedInIcon';
    import JetGithubIcon from '@/Socialite/GithubIcon';
    import JetGitLabIcon from '@/Socialite/GitLabIcon';
    import JetBitbucketIcon from '@/Socialite/BitbucketIcon';

    export default {
        props: {
            provider: String,
            createdAt: {
                type: String,
                defaut: null,
            }
        },

        components: {
            JetFacebookIcon,
            JetGoogleIcon,
            JetTwitterIcon,
            JetLinkedInIcon,
            JetGithubIcon,
            JetGitLabIcon,
            JetBitbucketIcon
        }
    }
</script>

<template>
    <div class="px-3 flex items-center justify-between">
        <div class="flex items-center">

            <jet-facebook-icon class="h-6 w-6 mr-2" v-if="provider === 'facebook'"/>
            <jet-google-icon class="h-6 w-6 mr-2" v-if="provider === 'google'"/>
            <jet-twitter-icon class="h-6 w-6 mr-2" v-if="provider === 'twitter'"/>
            <jet-linkedin-icon class="h-6 w-6 mr-2" v-if="provider === 'linkedin'"/>
            <jet-github-icon class="h-6 w-6 mr-2" v-if="provider === 'github'"/>
            <jet-git-lab-icon class="h-6 w-6 mr-2" v-if="provider === 'gitlab'"/>
            <jet-bitbucket-icon class="h-6 w-6 mr-2" v-if="provider === 'bitbucket'"/>

            <div>
                <div class="text-sm font-semibold text-gray-600">
                    {{ provider.charAt(0).toUpperCase() + provider.slice(1)  }}
                </div>

                <div class="text-xs text-gray-500">
                    Added on {{ createdAt }}
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
    import JetLinkedinIcon from '@/Socialite/LinkedinIcon';
    import JetGithubIcon from '@/Socialite/GithubIcon';
    import JetGitLabIcon from '@/Socialite/GitLabIcon';
    import JetBitbucketIcon from '@/Socialite/BitbucketIcon';

    export default {
        props: ['provider', 'createdAt'],

        components: {
            JetFacebookIcon,
            JetGoogleIcon,
            JetTwitterIcon,
            JetLinkedinIcon,
            JetGithubIcon,
            JetGitLabIcon,
            JetBitbucketIcon
        }
    }
</script>

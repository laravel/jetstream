<template>
    <div>
        <div class="flex flex-row items-center justify-between py-4 text-gray-500">
            <hr class="w-full mr-2">
                Or
            <hr class="w-full ml-2">
        </div>

        <div class="flex items-center justify-center">
            <a v-if="socialiteProviders.includes('facebook')" :href="route('socialite.redirect', {'provider': 'facebook'})">
                <jet-facebook-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.includes('google')" :href="route('socialite.redirect', {'provider': 'google'})" >
                <jet-google-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.includes('twitter')" :href="route('socialite.redirect', {'provider': 'twitter'})">
                <jet-twitter-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.includes('linkedin')" :href="route('socialite.redirect', {'provider': 'linkedin'})">
                <jet-linkedin-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.includes('github')" :href="route('socialite.redirect', {'provider': 'github'})">
                <jet-github-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.includes('gitlab')" :href="route('socialite.redirect', {'provider': 'gitlab'})">
                <jet-git-lab-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.includes('bitbucket')" :href="route('socialite.redirect', {'provider': 'bitbucket'})">
                <jet-bitbucket-icon class="h-6 w-6 mx-2" />
            </a>
        </div>
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
        props: {
            socialiteProviders: {
                type: Array,
                default: [],
            },
        },

        components: {
            JetFacebookIcon,
            JetGoogleIcon,
            JetTwitterIcon,
            JetLinkedinIcon,
            JetGithubIcon,
            JetGitLabIcon,
            JetBitbucketIcon,
        }
    }
</script>

<template>
    <div>
        <div class="flex flex-row items-center justify-between py-4 text-gray-500">
            <hr class="w-full mr-2">
                Or
            <hr class="w-full ml-2">
        </div>

        <div class="flex items-center justify-center">
            <a v-if="socialiteProviders.facebook" :href="route('socialite.redirect', {'provider': 'facebook'})">
                <jet-facebook-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.google" :href="route('socialite.redirect', {'provider': 'google'})" >
                <jet-google-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.twitter" :href="route('socialite.redirect', {'provider': 'twitter'})">
                <jet-twitter-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.linkedin" :href="route('socialite.redirect', {'provider': 'linkedin'})">
                <jet-linkedin-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.github" :href="route('socialite.redirect', {'provider': 'github'})">
                <jet-github-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.gitlab" :href="route('socialite.redirect', {'provider': 'gitlab'})">
                <jet-git-lab-icon class="h-6 w-6 mx-2" />
            </a>

            <a v-if="socialiteProviders.bitbucket" :href="route('socialite.redirect', {'provider': 'bitbucket'})">
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
            socialiteProviders: Object,
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

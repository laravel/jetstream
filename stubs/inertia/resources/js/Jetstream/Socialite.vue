<template>
    <div>
        <div class="flex flex-row items-center justify-between py-4 text-gray-500">
            <hr class="w-full mr-2">
                Or
            <hr class="w-full ml-2">
        </div>

        <div class="flex items-center justify-center">
            <!-- Facebook -->
            <a v-if="socialiteProviders.includes('facebook')" :href="route('socialite.redirect', {'provider': 'facebook'})">
                <jet-facebook-icon />
            </a>

            <!-- Google -->
            <a v-if="socialiteProviders.includes('google')" :href="route('socialite.redirect', {'provider': 'google'})" >
                <jet-google-icon />
            </a>

            <!-- Twitter -->
            <a v-if="socialiteProviders.includes('twitter')" :href="route('socialite.redirect', {'provider': 'twitter'})">
                <jet-twitter-icon />
            </a>

            <!-- LinkedIn -->
            <a v-if="socialiteProviders.includes('linkedin')" :href="route('socialite.redirect', {'provider': 'linkedin'})">
                <jet-linked-in-icon />
            </a>

            <!-- GitHub -->
            <a v-if="socialiteProviders.includes('github')" :href="route('socialite.redirect', {'provider': 'github'})">
                <jet-github-icon />
            </a>

            <!-- GitLab -->
            <a v-if="socialiteProviders.includes('gitlab')" :href="route('socialite.redirect', {'provider': 'gitlab'})">
                <jet-git-lab-icon />
            </a>

            <!-- Bitbucket -->
            <a v-if="socialiteProviders.includes('bitbucket')" :href="route('socialite.redirect', {'provider': 'bitbucket'})">
                <jet-bitbucket-icon />
            </a>
        </div>
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
            socialiteProviders: {
                type: Array,
                default: [],
            },
        },

        components: {
            JetFacebookIcon,
            JetGoogleIcon,
            JetTwitterIcon,
            JetLinkedInIcon,
            JetGithubIcon,
            JetGitLabIcon,
            JetBitbucketIcon,
        }
    }
</script>

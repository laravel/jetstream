<script setup>
import OAuthAppManager from '@/Pages/OAuth/Partials/OAuthAppManager.vue';
import OAuthConnectionManager from "@/Pages/OAuth/Partials/OAuthConnectionManager.vue";
import AppLayout from '@/Layouts/AppLayout.vue';

defineProps({
    connections: Array,
    apps: Array,
});
</script>

<template>
    <AppLayout title="OAuth Apps">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                OAuth Apps
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <OAuthConnectionManager :connections="connections" />

                <OAuthAppManager :apps="apps" />
            </div>
        </div>
    </AppLayout>
</template>

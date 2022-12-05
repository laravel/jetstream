<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import CreateCompanyForm from '@/Pages/Companies/Partials/CreateCompanyForm.vue';
</script>

<template>
    <AppLayout title="Create Company">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Create Company
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <CreateCompanyForm />
            </div>
        </div>
    </AppLayout>
</template>

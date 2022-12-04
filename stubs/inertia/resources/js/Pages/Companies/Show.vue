<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import DeleteCompanyForm from '@/Pages/Companies/Partials/DeleteCompanyForm.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import CompanyEmployeeManager from '@/Pages/Companies/Partials/CompanyEmployeeManager.vue';
import UpdateCompanyNameForm from '@/Pages/Companies/Partials/UpdateCompanyNameForm.vue';

defineProps({
    company: Object,
    availableRoles: Array,
    permissions: Object,
});
</script>

<template>
    <AppLayout title="Company Settings">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Company Settings
            </h2>
        </template>

        <div>
            <div class="max-w-7xl mx-auto py-10 sm:px-6 lg:px-8">
                <UpdateCompanyNameForm :company="company" :permissions="permissions" />

                <CompanyEmployeeManager
                    class="mt-10 sm:mt-0"
                    :company="company"
                    :available-roles="availableRoles"
                    :user-permissions="permissions"
                />

                <template v-if="permissions.canDeleteCompany && ! company.personal_company">
                    <SectionBorder />

                    <DeleteCompanyForm class="mt-10 sm:mt-0" :company="company" />
                </template>
            </div>
        </div>
    </AppLayout>
</template>

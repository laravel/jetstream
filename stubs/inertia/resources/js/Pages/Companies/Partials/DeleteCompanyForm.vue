<script setup>
import { ref } from 'vue';
import { useForm } from '@inertiajs/inertia-vue3';
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const props = defineProps({
    company: Object,
});

const confirmingCompanyDeletion = ref(false);
const form = useForm();

const confirmCompanyDeletion = () => {
    confirmingCompanyDeletion.value = true;
};

const deleteCompany = () => {
    form.delete(route('companies.destroy', props.company), {
        errorBag: 'deleteCompany',
    });
};
</script>

<template>
    <ActionSection>
        <template #title>
            Delete Company
        </template>

        <template #description>
            Permanently delete this company.
        </template>

        <template #content>
            <div class="max-w-xl text-sm text-gray-600">
                Once a company is deleted, all of its resources and data will be permanently deleted. Before deleting this company, please download any data or information regarding this company that you wish to retain.
            </div>

            <div class="mt-5">
                <DangerButton @click="confirmCompanyDeletion">
                    Delete Company
                </DangerButton>
            </div>

            <!-- Delete Company Confirmation Modal -->
            <ConfirmationModal :show="confirmingCompanyDeletion" @close="confirmingCompanyDeletion = false">
                <template #title>
                    Delete Company
                </template>

                <template #content>
                    Are you sure you want to delete this company? Once a company is deleted, all of its resources and data will be permanently deleted.
                </template>

                <template #footer>
                    <SecondaryButton @click="confirmingCompanyDeletion = false">
                        Cancel
                    </SecondaryButton>

                    <DangerButton
                        class="ml-3"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                        @click="deleteCompany"
                    >
                        Delete Company
                    </DangerButton>
                </template>
            </ConfirmationModal>
        </template>
    </ActionSection>
</template>

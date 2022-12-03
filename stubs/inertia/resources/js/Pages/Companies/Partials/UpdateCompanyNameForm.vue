<script setup>
import { useForm } from '@inertiajs/inertia-vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    company: Object,
    permissions: Object,
});

const form = useForm({
    name: props.company.name,
});

const updateCompanyName = () => {
    form.put(route('companies.update', props.company), {
        errorBag: 'updateCompanyName',
        preserveScroll: true,
    });
};
</script>

<template>
    <FormSection @submitted="updateCompanyName">
        <template #title>
            Company Name
        </template>

        <template #description>
            The company's name and owner information.
        </template>

        <template #form>
            <!-- Company Owner Information -->
            <div class="col-span-6">
                <InputLabel value="Company Owner" />

                <div class="flex items-center mt-2">
                    <img class="w-12 h-12 rounded-full object-cover" :src="company.owner.profile_photo_url" :alt="company.owner.name">

                    <div class="ml-4 leading-tight">
                        <div>{{ company.owner.name }}</div>
                        <div class="text-gray-700 text-sm">
                            {{ company.owner.email }}
                        </div>
                    </div>
                </div>
            </div>

            <!-- Company Name -->
            <div class="col-span-6 sm:col-span-4">
                <InputLabel for="name" value="Company Name" />

                <TextInput
                    id="name"
                    v-model="form.name"
                    type="text"
                    class="mt-1 block w-full"
                    :disabled="! permissions.canUpdateCompany"
                />

                <InputError :message="form.errors.name" class="mt-2" />
            </div>
        </template>

        <template v-if="permissions.canUpdateCompany" #actions>
            <ActionMessage :on="form.recentlySuccessful" class="mr-3">
                Saved.
            </ActionMessage>

            <PrimaryButton :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Save
            </PrimaryButton>
        </template>
    </FormSection>
</template>

<script setup>
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { useForm, usePage } from '@inertiajs/inertia-vue3';
import ActionMessage from '@/Components/ActionMessage.vue';
import ActionSection from '@/Components/ActionSection.vue';
import ConfirmationModal from '@/Components/ConfirmationModal.vue';
import DangerButton from '@/Components/DangerButton.vue';
import DialogModal from '@/Components/DialogModal.vue';
import FormSection from '@/Components/FormSection.vue';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import SectionBorder from '@/Components/SectionBorder.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    company: Object,
    availableRoles: Array,
    userPermissions: Object,
});

const addCompanyEmployeeForm = useForm({
    email: '',
    role: null,
});

const updateRoleForm = useForm({
    role: null,
});

const leaveCompanyForm = useForm();
const removeCompanyEmployeeForm = useForm();

const currentlyManagingRole = ref(false);
const managingRoleFor = ref(null);
const confirmingLeavingCompany = ref(false);
const companyEmployeeBeingRemoved = ref(null);

const addCompanyEmployee = () => {
    addCompanyEmployeeForm.post(route('company-employees.store', props.company), {
        errorBag: 'addCompanyEmployee',
        preserveScroll: true,
        onSuccess: () => addCompanyEmployeeForm.reset(),
    });
};

const cancelCompanyInvitation = (invitation) => {
    Inertia.delete(route('company-invitations.destroy', invitation), {
        preserveScroll: true,
    });
};

const manageRole = (companyEmployee) => {
    managingRoleFor.value = companyEmployee;
    updateRoleForm.role = companyEmployee.employeeship.role;
    currentlyManagingRole.value = true;
};

const updateRole = () => {
    updateRoleForm.put(route('company-employees.update', [props.company, managingRoleFor.value]), {
        preserveScroll: true,
        onSuccess: () => currentlyManagingRole.value = false,
    });
};

const confirmLeavingCompany = () => {
    confirmingLeavingCompany.value = true;
};

const leaveCompany = () => {
    leaveCompanyForm.delete(route('company-employees.destroy', [props.company, usePage().props.value.user]));
};

const confirmCompanyEmployeeRemoval = (companyEmployee) => {
    companyEmployeeBeingRemoved.value = companyEmployee;
};

const removeCompanyEmployee = () => {
    removeCompanyEmployeeForm.delete(route('company-employees.destroy', [props.company, companyEmployeeBeingRemoved.value]), {
        errorBag: 'removeCompanyEmployee',
        preserveScroll: true,
        preserveState: true,
        onSuccess: () => companyEmployeeBeingRemoved.value = null,
    });
};

const displayableRole = (role) => {
    return props.availableRoles.find(r => r.key === role).name;
};
</script>

<template>
    <div>
        <div v-if="userPermissions.canAddCompanyEmployees">
            <SectionBorder />

            <!-- Add Company Employee -->
            <FormSection @submitted="addCompanyEmployee">
                <template #title>
                    Add Company Employee
                </template>

                <template #description>
                    Add a new company employee to your company, allowing them to collaborate with you on your business endeavors.
                </template>

                <template #form>
                    <div class="col-span-6">
                        <div class="max-w-xl text-sm text-gray-600">
                            Please provide the email address of the employee you would like to add to this company.
                        </div>
                    </div>

                    <!-- Employee Email -->
                    <div class="col-span-6 sm:col-span-4">
                        <InputLabel for="email" value="Email" />
                        <TextInput
                            id="email"
                            v-model="addCompanyEmployeeForm.email"
                            type="email"
                            class="mt-1 block w-full"
                        />
                        <InputError :message="addCompanyEmployeeForm.errors.email" class="mt-2" />
                    </div>

                    <!-- Role -->
                    <div v-if="availableRoles.length > 0" class="col-span-6 lg:col-span-4">
                        <InputLabel for="roles" value="Role" />
                        <InputError :message="addCompanyEmployeeForm.errors.role" class="mt-2" />

                        <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                            <button
                                v-for="(role, i) in availableRoles"
                                :key="role.key"
                                type="button"
                                class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200"
                                :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i != Object.keys(availableRoles).length - 1}"
                                @click="addCompanyEmployeeForm.role = role.key"
                            >
                                <div :class="{'opacity-50': addCompanyEmployeeForm.role && addCompanyEmployeeForm.role != role.key}">
                                    <!-- Role Name -->
                                    <div class="flex items-center">
                                        <div class="text-sm text-gray-600" :class="{'font-semibold': addCompanyEmployeeForm.role == role.key}">
                                            {{ role.name }}
                                        </div>

                                        <svg
                                            v-if="addCompanyEmployeeForm.role == role.key"
                                            class="ml-2 h-5 w-5 text-green-400"
                                            fill="none"
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            stroke-width="2"
                                            stroke="currentColor"
                                            viewBox="0 0 24 24"
                                        ><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                    </div>

                                    <!-- Role Description -->
                                    <div class="mt-2 text-xs text-gray-600 text-left">
                                        {{ role.description }}
                                    </div>
                                </div>
                            </button>
                        </div>
                    </div>
                </template>

                <template #actions>
                    <ActionMessage :on="addCompanyEmployeeForm.recentlySuccessful" class="mr-3">
                        Added.
                    </ActionMessage>

                    <PrimaryButton :class="{ 'opacity-25': addCompanyEmployeeForm.processing }" :disabled="addCompanyEmployeeForm.processing">
                        Add
                    </PrimaryButton>
                </template>
            </FormSection>
        </div>

        <div v-if="(company.company_invitations.length > 0 && userPermissions.canAddCompanyEmployees)">
            <SectionBorder />

            <!-- Company Employee Invitations -->
            <ActionSection class="mt-10 sm:mt-0">
                <template #title>
                    Pending Company Invitations
                </template>

                <template #description>
                    These employees have been invited to your company and have been sent an invitation email. They may join the company by accepting the email invitation.
                </template>

                <!-- Pending Company Employee Invitation List -->
                <template #content>
                    <div class="space-y-6">
                        <div v-for="invitation in company.company_invitations" :key="invitation.id" class="flex items-center justify-between">
                            <div class="text-gray-600">
                                {{ invitation.email }}
                            </div>

                            <div class="flex items-center">
                                <!-- Cancel Company Invitation -->
                                <button
                                    v-if="userPermissions.canRemoveCompanyEmployees"
                                    class="cursor-pointer ml-6 text-sm text-red-500 focus:outline-none"
                                    @click="cancelCompanyInvitation(invitation)"
                                >
                                    Cancel
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </ActionSection>
        </div>

        <div v-if="company.users.length > 0">
            <SectionBorder />

            <!-- Manage Company Employees -->
            <ActionSection class="mt-10 sm:mt-0">
                <template #title>
                    Company Employees
                </template>

                <template #description>
                    All of the employees that are part of this company.
                </template>

                <!-- Company Employee List -->
                <template #content>
                    <div class="space-y-6">
                        <div v-for="user in company.users" :key="user.id" class="flex items-center justify-between">
                            <div class="flex items-center">
                                <img class="w-8 h-8 rounded-full" :src="user.profile_photo_url" :alt="user.name">
                                <div class="ml-4">
                                    {{ user.name }}
                                </div>
                            </div>

                            <div class="flex items-center">
                                <!-- Manage Company Employee Role -->
                                <button
                                    v-if="userPermissions.canAddCompanyEmployees && availableRoles.length"
                                    class="ml-2 text-sm text-gray-400 underline"
                                    @click="manageRole(user)"
                                >
                                    {{ displayableRole(user.employeeship.role) }}
                                </button>

                                <div v-else-if="availableRoles.length" class="ml-2 text-sm text-gray-400">
                                    {{ displayableRole(user.employeeship.role) }}
                                </div>

                                <!-- Leave Company -->
                                <button
                                    v-if="$page.props.user.id === user.id"
                                    class="cursor-pointer ml-6 text-sm text-red-500"
                                    @click="confirmLeavingCompany"
                                >
                                    Leave
                                </button>

                                <!-- Remove Company Employee -->
                                <button
                                    v-else-if="userPermissions.canRemoveCompanyEmployees"
                                    class="cursor-pointer ml-6 text-sm text-red-500"
                                    @click="confirmCompanyEmployeeRemoval(user)"
                                >
                                    Remove
                                </button>
                            </div>
                        </div>
                    </div>
                </template>
            </ActionSection>
        </div>

        <!-- Role Management Modal -->
        <DialogModal :show="currentlyManagingRole" @close="currentlyManagingRole = false">
            <template #title>
                Manage Role
            </template>

            <template #content>
                <div v-if="managingRoleFor">
                    <div class="relative z-0 mt-1 border border-gray-200 rounded-lg cursor-pointer">
                        <button
                            v-for="(role, i) in availableRoles"
                            :key="role.key"
                            type="button"
                            class="relative px-4 py-3 inline-flex w-full rounded-lg focus:z-10 focus:outline-none focus:border-blue-300 focus:ring focus:ring-blue-200"
                            :class="{'border-t border-gray-200 rounded-t-none': i > 0, 'rounded-b-none': i !== Object.keys(availableRoles).length - 1}"
                            @click="updateRoleForm.role = role.key"
                        >
                            <div :class="{'opacity-50': updateRoleForm.role && updateRoleForm.role !== role.key}">
                                <!-- Role Name -->
                                <div class="flex items-center">
                                    <div class="text-sm text-gray-600" :class="{'font-semibold': updateRoleForm.role === role.key}">
                                        {{ role.name }}
                                    </div>

                                    <svg
                                        v-if="updateRoleForm.role === role.key"
                                        class="ml-2 h-5 w-5 text-green-400"
                                        fill="none"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        stroke="currentColor"
                                        viewBox="0 0 24 24"
                                    ><path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                                </div>

                                <!-- Role Description -->
                                <div class="mt-2 text-xs text-gray-600">
                                    {{ role.description }}
                                </div>
                            </div>
                        </button>
                    </div>
                </div>
            </template>

            <template #footer>
                <SecondaryButton @click="currentlyManagingRole = false">
                    Cancel
                </SecondaryButton>

                <PrimaryButton
                    class="ml-3"
                    :class="{ 'opacity-25': updateRoleForm.processing }"
                    :disabled="updateRoleForm.processing"
                    @click="updateRole"
                >
                    Save
                </PrimaryButton>
            </template>
        </DialogModal>

        <!-- Leave Company Confirmation Modal -->
        <ConfirmationModal :show="confirmingLeavingCompany" @close="confirmingLeavingCompany = false">
            <template #title>
                Leave Company
            </template>

            <template #content>
                Are you sure you would like to leave this company?
            </template>

            <template #footer>
                <SecondaryButton @click="confirmingLeavingCompany = false">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': leaveCompanyForm.processing }"
                    :disabled="leaveCompanyForm.processing"
                    @click="leaveCompany"
                >
                    Leave
                </DangerButton>
            </template>
        </ConfirmationModal>

        <!-- Remove Company Employee Confirmation Modal -->
        <ConfirmationModal :show="companyEmployeeBeingRemoved" @close="companyEmployeeBeingRemoved = null">
            <template #title>
                Remove Company Employee
            </template>

            <template #content>
                Are you sure you would like to remove this employee from the company?
            </template>

            <template #footer>
                <SecondaryButton @click="companyEmployeeBeingRemoved = null">
                    Cancel
                </SecondaryButton>

                <DangerButton
                    class="ml-3"
                    :class="{ 'opacity-25': removeCompanyEmployeeForm.processing }"
                    :disabled="removeCompanyEmployeeForm.processing"
                    @click="removeCompanyEmployee"
                >
                    Remove
                </DangerButton>
            </template>
        </ConfirmationModal>
    </div>
</template>

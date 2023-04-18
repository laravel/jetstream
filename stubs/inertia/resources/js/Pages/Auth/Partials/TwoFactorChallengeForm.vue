<script setup>
import { nextTick, ref } from 'vue';
import { useForm } from '@inertiajs/vue3';
import InputError from '@/Components/InputError.vue';
import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';

const props = defineProps({
    index: String,
    label: String,
})

const emit = defineEmits(['toggle']);

const form = useForm({
    [props.index]: '',
});

const input = ref(null);

nextTick(() => {
    input.value.focus();
    form[props.index] = '';
});

const submit = () => {
    form.post(route('two-factor.login'));
};

const toggle = () => emit('toggle');
</script>

<template>
    <div class="mb-4 text-sm text-gray-600 dark:text-gray-400">
        <slot name="description" />
    </div>

    <form @submit.prevent="submit">
        <div>
            <InputLabel :for="index" :value="label" />
            <TextInput
                :id="index"
                ref="input"
                v-model="form[index]"
                type="text"
                class="mt-1 block w-full"
                autocomplete="one-time-code"
            />
            <InputError class="mt-2" :message="form.errors[index]" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <button type="button" class="text-sm text-gray-600 dark:text-gray-400 hover:text-gray-900 underline cursor-pointer" @click.prevent="toggle">
                <slot name="toggle" />
            </button>

            <PrimaryButton class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                Log in
            </PrimaryButton>
        </div>
    </form>
</template>

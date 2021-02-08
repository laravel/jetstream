<template>
    <input type="checkbox" :value="modelValue"  @input="$emit('update:modelValue', $event.target.value)"
           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
</template>

<script>
    export default {
        emits: ['update:modelValue', 'change'],

        // props: {
        //     checked: {
        //         type: [Array, Boolean],
        //         default: false,
        //     },
        //     modelValue: {
        //         type: [Array, Boolean],
        //         default: false,
        //     },
        // },
        props: ['modelValue'],

        computed: {
            proxyChecked: {
                get() {
                    return this.checked;
                },
                set(val) {
                    this.$emit("change", val);
                },
            },
        },
    }
</script>


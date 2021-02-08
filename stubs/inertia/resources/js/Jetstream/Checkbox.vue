<template>
    <input type="checkbox" :value="modelValue" v-model="proxyChecked"
           class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
</template>

<script>
    export default {
        emits: ['update:modelValue'],

        props: {
            checked: {
                type: [Array, Boolean],
                default: false,
            },
            modelValue: {
                type: [Array, Boolean],
                default: false,
            },
        },

        computed: {
            proxyChecked: {
                get() {
                    return this.checked;
                },
                set(val) {
                    this.$emit("update:modelValue", val);
                },
            },
        },
    }
</script>


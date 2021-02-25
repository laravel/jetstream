<template>
    <input type="checkbox" :value="value" v-model="proxyChecked"
           class="text-indigo-600 border-gray-300 rounded shadow-sm dark:border-gray-600 dark:bg-gray-700 focus:border-indigo-300 dark:focus:border-indigo-500 focus:ring focus:ring-indigo-200 dark:focus:ring-indigo-400 focus:ring-opacity-50">
</template>

<script>
export default {
    emits: ['update:checked'],

    props: {
        checked: {
            type: [Array, Boolean],
            default: false,
        },
        value: {
            default: null,
        },
    },

    computed: {
        proxyChecked: {
            get() {
                return this.checked;
            },

            set(val) {
                this.$emit("update:checked", val);
            },
        },
    },
}
</script>

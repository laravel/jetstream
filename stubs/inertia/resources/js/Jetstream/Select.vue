<template>
    <select class="form-select rounded-md shadow-sm" :value="value" @input="$emit('input', $event.target.value)" ref="select">
        <option v-if="placeholder" :value="null" disabled>{{ placeholder }}</option>
        <slot></slot>
    </select>
</template>

<script>
    export default {
        props: {
            value: {
                required: false,
            },
            placeholder: {
                type: String,
                required: false,
            }
        },

        methods: {
            focus() {
                this.$refs.select.focus()
            }
        }
    }
</script>

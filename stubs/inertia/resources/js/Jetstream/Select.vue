<template>
    <div class='relative'>
        <select class="form-input w-full rounded-md shadow-sm" v-bind='$attrs' :value='value' @input="$emit('input', $event.target.value)" ref="input">
            <option v-if='placeholder' :value='null' disabled>{{ placeholder }}</option>
            <slot></slot>
        </select>
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" class='absolute fill-current text-gray-300 right-0 -mt-2 mr-2 h-4 w-4 pointer-events-none' style='top: 50%'>
            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
        </svg>
    </div>
</template>

<script>
    export default {
        inheritAttrs: false,
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
                this.$refs.input.focus()
            }
        }
    }
</script>

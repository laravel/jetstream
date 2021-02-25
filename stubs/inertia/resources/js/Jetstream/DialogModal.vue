<template>
    <modal :show="show" :max-width="maxWidth" :closeable="closeable" @close="close">
        <div class="px-6 py-4">
            <div class="text-lg dark:text-gray-200">
                <slot name="title">
                </slot>
            </div>

            <div class="mt-4 dark:text-gray-400">
                <slot name="content">
                </slot>
            </div>
        </div>

        <div class="px-6 py-4 text-right bg-gray-100 dark:bg-gray-700">
            <slot name="footer">
            </slot>
        </div>
    </modal>
</template>

<script>
    import Modal from './Modal'

    export default {
        emits: ['close'],

        components: {
            Modal,
        },

        props: {
            show: {
                default: false
            },
            maxWidth: {
                default: '2xl'
            },
            closeable: {
                default: true
            },
        },

        methods: {
            close() {
                this.$emit('close')
            },
        }
    }
</script>

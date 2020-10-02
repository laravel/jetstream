<template>
    <div class="md:grid md:grid-cols-3 md:gap-6">
        <jet-section-title>
            <template #title>
                <slot name="title" />
            </template>
            <template #description>
                <slot name="description" />
            </template>
        </jet-section-title>

        <div class="mt-5 md:mt-0 md:col-span-2">
            <form @submit.prevent="$emit('submitted')">
                <div class="shadow overflow-hidden sm:rounded-md">
                    <div class="px-4 py-5 bg-white sm:p-6">
                        <div class="grid grid-cols-6 gap-6">
                            <slot name="form" />
                        </div>
                    </div>

                    <div
                        class="flex items-center justify-end px-4 py-3 bg-gray-50 text-right sm:px-6"
                        v-if="hasActions"
                    >
                        <slot name="actions" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</template>

<script>
import JetSectionTitle from './SectionTitle'

export default {
    components: {
        JetSectionTitle,
    },

    computed: {
        hasActions() {
            return !! this.$slots.actions
        }
    }
}
</script>

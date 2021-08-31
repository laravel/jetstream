<template>
    <label class="block font-medium text-sm text-gray-700">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot></slot></span>
    </label>
</template>

<script>
    import { defineComponent } from 'vue'

    export default defineComponent({
        props: ['value']
    })
</script>

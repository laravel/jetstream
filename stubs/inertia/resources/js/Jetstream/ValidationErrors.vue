<template>
    <div v-if="hasErrors">
        <div class="font-medium text-red-600">Whoops! Something went wrong.</div>

        <ul class="mt-3 list-disc list-inside text-sm text-red-600">
            <li v-for="(error, key) in flattenedErrors" :key="key">{{ error }}</li>
        </ul>
    </div>
</template>

<script>
    export default {
        props: {
            bag: {
                type: String,
                default: 'default',
            },
        },

        computed: {
            errorBag() {
                return this.$page.errorBags[this.bag] || {}
            },

            hasErrors() {
                return Object.keys(this.errorBag).length > 0;
            },

            flattenedErrors() {
                return Object.keys(this.errorBag).reduce((carry, key) => carry.concat(this.errorBag[key]), [])
            }
        }
    }
</script>

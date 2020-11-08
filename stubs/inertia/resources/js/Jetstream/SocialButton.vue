<template>
    <a :href="href" :style="styles">
        <slot></slot>
    </a>
</template>

<script>
    export default {
        props: {
            href: {
                type: String,
            },
            color: {
                type: String,
                default: null,
            }
        },

        computed: {
            styles() {
                return this.color !== null
                    ? `color: ${this.color};`
                    : 'color: black';
            },
        }
    }
</script>

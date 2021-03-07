<template>
    <div class="text-sm">
        <transition-group leave-active-class="transition ease-in duration-1000" leave-from-class="opacity-100" leave-to-class="opacity-0">
            <span v-show="on || recentlySuccessful" key="successful" class="text-gray-600">
                <slot>Saved.</slot>
            </span>
            <span v-if="!!onError" v-show="recentlyError" key="error" class="text-red-600">
                <slot name="error">Whoops! Something went wrong.</slot>
            </span>
        </transition-group>
    </div>
</template>

<script>
let recentlyActionTimeoutId = null;

export default {
    props: {
        on: {
            type: Boolean,
            default: false,
            required: false
        },
        onSuccess: {
            type: Boolean,
            default: false,
            required: false,
        },
        onError: {
            type: Boolean,
            default: null,
            required: false,
        }
    },
    watch: {
        onSuccess(value) {
            if (value) {
                this.timer('recentlySuccessful')
            }
        },
        onError(value) {
            if (value) {
                this.timer('recentlyError')
            }
        }
    },
    data() {
        return {
            recentlySuccessful: false,
            recentlyError: false,
        }
    },
    methods: {
        timer(variable) {
            this[variable] = true
            clearTimeout(recentlyActionTimeoutId)
            recentlyActionTimeoutId = setTimeout(() => this[variable] = false, 2000)
        }
    }
}
</script>

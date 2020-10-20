<template>
    <div>
        <div class="bg-indigo-500" v-if="show && message">
            <div class="max-w-screen-xl mx-auto py-2 px-3 sm:px-6 lg:px-8">
                <div class="flex items-center justify-between flex-wrap">
                    <div class="w-0 flex-1 flex items-center">
                        <span class="flex p-2 rounded-lg bg-indigo-600">
                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </span>

                        <p class="ml-3 font-medium text-sm text-white truncate">
                            <span class="md:hidden">
                                {{ message }}
                            </span>

                            <span class="hidden md:inline">
                                {{ message }}
                            </span>
                        </p>
                    </div>

                    <div class="flex-shrink-0 sm:ml-3">
                        <button
                        type="button"
                        class="-mr-1 flex p-2 rounded-md hover:bg-indigo-600 focus:outline-none focus:bg-indigo-600 sm:-mr-2 transition ease-in-out duration-150"
                        aria-label="Dismiss"
                        @click.prevent="show = false">
                            <svg class="h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                show: true,
                message: this.$page.props.jetstream.flash?.banner || '',
            }
        }
    }
</script>

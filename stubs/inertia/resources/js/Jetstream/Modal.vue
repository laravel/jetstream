<template>
    <teleport to="body">
        <transition leave-active-class="duration-200">
            <div v-show="show" class="fixed inset-0 z-50 px-4 py-6 overflow-y-auto sm:px-0">
                <transition enter-active-class="duration-300 ease-out"
                        enter-from-class="opacity-0"
                        enter-to-class="opacity-100"
                        leave-active-class="duration-200 ease-in"
                        leave-from-class="opacity-100"
                        leave-to-class="opacity-0">
                    <div v-show="show" class="fixed inset-0 transition-all transform" @click="close">
                        <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
                    </div>
                </transition>

                <transition enter-active-class="duration-300 ease-out"
                        enter-from-class="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95"
                        enter-to-class="translate-y-0 opacity-100 sm:scale-100"
                        leave-active-class="duration-200 ease-in"
                        leave-from-class="translate-y-0 opacity-100 sm:scale-100"
                        leave-to-class="translate-y-4 opacity-0 sm:translate-y-0 sm:scale-95">
                    <div v-show="show" class="mb-6 overflow-hidden transition-all transform bg-white rounded-lg shadow-xl dark:bg-gray-800 sm:w-full sm:mx-auto" :class="maxWidthClass">
                        <slot></slot>
                    </div>
                </transition>
            </div>
        </transition>
    </teleport>
</template>

<script>
import { onMounted, onUnmounted } from "vue";

export default {
        emits: ['close'],

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

        watch: {
            show: {
                immediate: true,
                handler: (show) => {
                    if (show) {
                        document.body.style.overflow = 'hidden'
                    } else {
                        document.body.style.overflow = null
                    }
                }
            }
        },

        setup(props, {emit}) {
            const close = () => {
                if (props.closeable) {
                    emit('close')
                }
            }

            const closeOnEscape = (e) => {
                if (e.key === 'Escape' && props.show) {
                    close()
                }
            }

            onMounted(() => document.addEventListener('keydown', closeOnEscape))
            onUnmounted(() => document.removeEventListener('keydown', closeOnEscape))

            return {
                close,
            }
        },

        computed: {
            maxWidthClass() {
                return {
                    'sm': 'sm:max-w-sm',
                    'md': 'sm:max-w-md',
                    'lg': 'sm:max-w-lg',
                    'xl': 'sm:max-w-xl',
                    '2xl': 'sm:max-w-2xl',
                }[this.maxWidth]
            }
        }
    }
</script>

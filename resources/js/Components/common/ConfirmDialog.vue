<script setup lang="ts">
import { watch, onBeforeUnmount } from 'vue'
import { AlertTriangle, Loader2 } from 'lucide-vue-next'

interface Props {
    open: boolean
    title?: string
    message?: string
    confirmLabel?: string
    cancelLabel?: string
    variant?: 'danger' | 'primary'
    loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    title: 'Are you sure?',
    message: 'This action cannot be undone.',
    confirmLabel: 'Confirm',
    cancelLabel: 'Cancel',
    variant: 'danger',
    loading: false,
})

const emit = defineEmits<{
    'update:open': [value: boolean]
    confirm: []
    cancel: []
}>()

const close = () => {
    if (props.loading) return
    emit('update:open', false)
    emit('cancel')
}

const confirm = () => {
    if (props.loading) return
    emit('confirm')
}

const onKeydown = (e: KeyboardEvent) => {
    if (e.key === 'Escape') close()
}

watch(
    () => props.open,
    (isOpen) => {
        if (typeof document === 'undefined') return
        if (isOpen) {
            document.addEventListener('keydown', onKeydown)
            document.body.style.overflow = 'hidden'
        } else {
            document.removeEventListener('keydown', onKeydown)
            document.body.style.overflow = ''
        }
    },
)

onBeforeUnmount(() => {
    if (typeof document === 'undefined') return
    document.removeEventListener('keydown', onKeydown)
    document.body.style.overflow = ''
})
</script>

<template>
    <Teleport to="body">
            <div
                v-if="open"
                class="fixed inset-0 z-[100] flex items-center justify-center p-4"
            >
                <!-- Backdrop -->
                <div
                    class="absolute inset-0 bg-slate-900/50 backdrop-blur-sm"
                    @click="close"
                ></div>

                <!-- Panel -->
                    <div
                        role="dialog"
                        aria-modal="true"
                        class="relative w-full max-w-md rounded-2xl bg-white p-6 shadow-xl"
                    >
                        <div class="flex items-start gap-4">
                            <div
                                :class="[
                                    'flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-2xl',
                                    variant === 'danger' ? 'bg-rose-100 text-rose-600' : 'accent-tint accent-text',
                                ]"
                            >
                                <AlertTriangle class="h-6 w-6" />
                            </div>
                            <div class="min-w-0 flex-1 pt-0.5">
                                <h3 class="text-lg font-semibold text-slate-900">{{ title }}</h3>
                                <p class="mt-1 text-sm text-slate-500">{{ message }}</p>
                            </div>
                        </div>

                        <div class="mt-6 flex items-center justify-end gap-3">
                            <button
                                type="button"
                                class="inline-flex items-center rounded-xl border border-line bg-white px-4 py-2.5 text-sm font-semibold text-slate-700 shadow-sm transition-colors hover:bg-slate-50 disabled:opacity-60"
                                :disabled="loading"
                                @click="close"
                            >
                                {{ cancelLabel }}
                            </button>
                            <button
                                type="button"
                                :class="[
                                    'inline-flex items-center gap-2 rounded-xl px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:-translate-y-0.5 hover:shadow-md disabled:cursor-not-allowed disabled:opacity-70 disabled:hover:translate-y-0',
                                    variant === 'danger'
                                        ? 'bg-error-600 hover:shadow-rose-500/30'
                                        : 'bg-ink-900 hover:shadow-indigo-500/30',
                                ]"
                                :disabled="loading"
                                @click="confirm"
                            >
                                <Loader2 v-if="loading" class="h-4 w-4 animate-spin" />
                                {{ confirmLabel }}
                            </button>
                        </div>
                    </div>
            </div>
    </Teleport>
</template>

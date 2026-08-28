<script setup lang="ts">
import { ref, watch, onMounted } from 'vue'
import { usePage } from '@inertiajs/vue3'
import Toast from './Toast.vue'

interface ToastItem {
    id: number
    type: 'success' | 'error' | 'warning' | 'info'
    title?: string
    message: string
    show: boolean
}

const toasts = ref<ToastItem[]>([])
let toastId = 0

const page = usePage()

const addToast = (toast: Omit<ToastItem, 'id' | 'show'>) => {
    const id = ++toastId
    toasts.value.push({
        ...toast,
        id,
        show: true,
    })

    // Auto-dismiss after 5 seconds
    setTimeout(() => {
        removeToast(id)
    }, 5000)
}

const removeToast = (id: number) => {
    const index = toasts.value.findIndex(t => t.id === id)
    if (index > -1) {
        toasts.value[index].show = false
        // Remove from array after animation
        setTimeout(() => {
            toasts.value = toasts.value.filter(t => t.id !== id)
        }, 300)
    }
}

// Watch for Inertia flash messages
watch(
    () => page.props.flash,
    (flash: any) => {
        if (flash?.success) {
            addToast({
                type: 'success',
                message: flash.success,
            })
        }
        if (flash?.error) {
            addToast({
                type: 'error',
                message: flash.error,
            })
        }
        if (flash?.warning) {
            addToast({
                type: 'warning',
                message: flash.warning,
            })
        }
        if (flash?.info) {
            addToast({
                type: 'info',
                message: flash.info,
            })
        }
        if (flash?.message) {
            addToast({
                type: 'info',
                message: flash.message,
            })
        }
    },
    { immediate: true, deep: true }
)

// Expose addToast globally
onMounted(() => {
    (window as any).$toast = {
        success: (message: string, title?: string) => addToast({ type: 'success', message, title }),
        error: (message: string, title?: string) => addToast({ type: 'error', message, title }),
        warning: (message: string, title?: string) => addToast({ type: 'warning', message, title }),
        info: (message: string, title?: string) => addToast({ type: 'info', message, title }),
    }
})
</script>

<template>
    <Teleport to="body">
        <div class="fixed top-4 right-4 z-[100] space-y-2">
            <Toast
                v-for="toast in toasts"
                :key="toast.id"
                :type="toast.type"
                :title="toast.title"
                :message="toast.message"
                :show="toast.show"
                @close="removeToast(toast.id)"
            />
        </div>
    </Teleport>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import {
    CheckCircle,
    AlertCircle,
    Info,
    X,
    AlertTriangle,
} from 'lucide-vue-next'

interface Props {
    type?: 'success' | 'error' | 'warning' | 'info'
    title?: string
    message: string
    show?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    type: 'info',
    show: true,
})

const emit = defineEmits<{
    close: []
}>()

const config = computed(() => {
    const configs = {
        success: {
            icon: CheckCircle,
            bg: 'bg-green-50',
            border: 'border-green-200',
            iconColor: 'text-green-600',
            titleColor: 'text-green-800',
            textColor: 'text-green-700',
        },
        error: {
            icon: AlertCircle,
            bg: 'bg-red-50',
            border: 'border-red-200',
            iconColor: 'text-red-600',
            titleColor: 'text-red-800',
            textColor: 'text-red-700',
        },
        warning: {
            icon: AlertTriangle,
            bg: 'bg-amber-50',
            border: 'border-amber-200',
            iconColor: 'text-amber-600',
            titleColor: 'text-amber-800',
            textColor: 'text-amber-700',
        },
        info: {
            icon: Info,
            bg: 'bg-blue-50',
            border: 'border-blue-200',
            iconColor: 'text-blue-600',
            titleColor: 'text-blue-800',
            textColor: 'text-blue-700',
        },
    }
    return configs[props.type]
})
</script>

<template>
    <Transition
        enter-active-class="transition ease-out duration-300"
        enter-from-class="transform translate-x-full opacity-0"
        enter-to-class="transform translate-x-0 opacity-100"
        leave-active-class="transition ease-in duration-200"
        leave-from-class="transform translate-x-0 opacity-100"
        leave-to-class="transform translate-x-full opacity-0"
    >
        <div
            v-if="show"
            :class="[
                'flex items-start gap-3 p-4 rounded-lg border shadow-lg max-w-sm',
                config.bg,
                config.border,
            ]"
        >
            <component
                :is="config.icon"
                :class="['w-5 h-5 flex-shrink-0 mt-0.5', config.iconColor]"
            />

            <div class="flex-1 min-w-0">
                <p
                    v-if="title"
                    :class="['text-sm font-semibold', config.titleColor]"
                >
                    {{ title }}
                </p>
                <p :class="['text-sm', config.textColor]">
                    {{ message }}
                </p>
            </div>

            <button
                @click="emit('close')"
                class="flex-shrink-0 p-1 rounded hover:bg-black/5 transition-colors"
            >
                <X class="w-4 h-4 text-slate-400" />
            </button>
        </div>
    </Transition>
</template>

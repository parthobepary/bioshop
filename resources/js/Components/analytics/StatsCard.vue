<script setup lang="ts">
import { computed } from 'vue'
import { Minus, TrendingDown, TrendingUp } from 'lucide-vue-next'

type Tone = 'ink' | 'accent' | 'success' | 'warning' | 'error'

interface Props {
    title: string
    value: number | string
    icon: any
    /** Colour of the icon chip. Everything else stays neutral. */
    tone?: Tone
    change?: number | null
    changeLabel?: string
    loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    tone: 'ink',
    change: null,
    changeLabel: 'vs last period',
    loading: false,
})

const toneClass: Record<Tone, string> = {
    ink: 'border-line bg-paper-subtle text-ink-700',
    accent: 'accent-border-soft accent-tint accent-text',
    success: 'border-success-100 bg-success-50 text-success-600',
    warning: 'border-warning-100 bg-warning-50 text-warning-600',
    error: 'border-error-100 bg-error-50 text-error-600',
}

const formattedValue = computed(() => {
    if (typeof props.value === 'number') {
        return new Intl.NumberFormat().format(props.value)
    }
    return props.value
})

const changeType = computed(() => {
    if (props.change === null || props.change === 0) return 'neutral'
    return props.change > 0 ? 'positive' : 'negative'
})

const changeIcon = computed(() => {
    if (changeType.value === 'positive') return TrendingUp
    if (changeType.value === 'negative') return TrendingDown
    return Minus
})

const changeClass = computed(() => {
    if (changeType.value === 'positive') return 'text-success-600'
    if (changeType.value === 'negative') return 'text-error-600'
    return 'text-ink-400'
})
</script>

<template>
    <div class="card card-hover p-4">
        <div class="flex items-start justify-between gap-3">
            <div class="min-w-0 flex-1">
                <p class="text-[13px] font-medium text-ink-500">{{ title }}</p>

                <div v-if="loading" class="mt-2 h-7 w-20 animate-pulse rounded bg-paper-deep"></div>
                <p v-else class="mt-1 font-display text-2xl font-semibold tracking-tight text-ink-900">
                    {{ formattedValue }}
                </p>

                <div v-if="change !== null" class="mt-1.5 flex items-center gap-1">
                    <component :is="changeIcon" :class="['h-3.5 w-3.5', changeClass]" />
                    <span :class="['text-[12px] font-semibold', changeClass]">{{ Math.abs(change) }}%</span>
                    <span class="text-[12px] text-ink-400">{{ changeLabel }}</span>
                </div>
            </div>

            <span :class="['flex h-9 w-9 shrink-0 items-center justify-center rounded-lg border', toneClass[tone]]">
                <component :is="icon" class="h-4 w-4" />
            </span>
        </div>
    </div>
</template>

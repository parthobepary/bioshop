<script setup lang="ts">
import { computed } from 'vue'
import { TrendingUp, TrendingDown, Minus } from 'lucide-vue-next'

interface Props {
    title: string
    value: number | string
    icon: any
    // Tailwind gradient stops for the icon chip, e.g. 'from-blue-500 to-indigo-500'
    accent?: string
    change?: number | null
    changeLabel?: string
    loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    accent: 'from-indigo-500 to-purple-500',
    change: null,
    changeLabel: 'vs last period',
    loading: false,
})

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
    if (changeType.value === 'positive') return 'text-emerald-600'
    if (changeType.value === 'negative') return 'text-rose-600'
    return 'text-slate-400'
})
</script>

<template>
    <div
        class="group relative overflow-hidden rounded-2xl border border-slate-200/70 bg-white p-5 shadow-sm transition-all duration-300 hover:-translate-y-0.5 hover:shadow-lg hover:shadow-slate-200/60"
    >
        <!-- Soft accent wash in the corner -->
        <div
            :class="[
                'pointer-events-none absolute -right-6 -top-6 h-24 w-24 rounded-full bg-gradient-to-br opacity-[0.07] blur-xl transition-opacity duration-300 group-hover:opacity-20',
                accent,
            ]"
        ></div>

        <div class="relative flex items-start justify-between gap-3">
            <div class="min-w-0 flex-1">
                <p class="text-sm font-medium text-slate-500">{{ title }}</p>
                <div v-if="loading" class="mt-2 h-8 w-20 animate-pulse rounded bg-slate-200"></div>
                <p v-else class="mt-1.5 text-3xl font-bold tracking-tight text-slate-900">
                    {{ formattedValue }}
                </p>

                <div v-if="change !== null" class="mt-2 flex items-center gap-1">
                    <component :is="changeIcon" :class="['h-4 w-4', changeClass]" />
                    <span :class="['text-sm font-semibold', changeClass]">{{ Math.abs(change) }}%</span>
                    <span class="text-sm text-slate-400">{{ changeLabel }}</span>
                </div>
            </div>

            <div
                :class="[
                    'flex h-12 w-12 flex-shrink-0 items-center justify-center rounded-xl bg-gradient-to-br text-white shadow-sm',
                    accent,
                ]"
            >
                <component :is="icon" class="h-6 w-6" />
            </div>
        </div>
    </div>
</template>

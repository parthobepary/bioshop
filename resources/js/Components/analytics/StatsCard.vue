<script setup lang="ts">
import { computed } from 'vue'
import { TrendingUp, TrendingDown, Minus } from 'lucide-vue-next'

interface Props {
    title: string
    value: number | string
    icon: any
    iconBgClass?: string
    iconClass?: string
    change?: number | null
    changeLabel?: string
    loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    iconBgClass: 'bg-primary-100',
    iconClass: 'text-primary-600',
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
    if (changeType.value === 'positive') return 'text-green-600'
    if (changeType.value === 'negative') return 'text-red-600'
    return 'text-gray-500'
})
</script>

<template>
    <div class="bg-white rounded-2xl border border-gray-100 p-5 hover:shadow-md transition-shadow">
        <div class="flex items-start justify-between">
            <div class="flex-1">
                <p class="text-sm font-medium text-gray-500">{{ title }}</p>
                <div v-if="loading" class="mt-2 h-8 w-20 bg-gray-200 rounded animate-pulse"></div>
                <p v-else class="mt-2 text-2xl font-bold text-gray-900">{{ formattedValue }}</p>

                <div v-if="change !== null" class="mt-2 flex items-center gap-1">
                    <component :is="changeIcon" :class="['w-4 h-4', changeClass]" />
                    <span :class="['text-sm font-medium', changeClass]">
                        {{ Math.abs(change) }}%
                    </span>
                    <span class="text-sm text-gray-400">{{ changeLabel }}</span>
                </div>
            </div>
            <div :class="['w-12 h-12 rounded-xl flex items-center justify-center', iconBgClass]">
                <component :is="icon" :class="['w-6 h-6', iconClass]" />
            </div>
        </div>
    </div>
</template>

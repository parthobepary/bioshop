<script setup lang="ts">
import { computed } from 'vue'

interface Item {
    id: number
    name?: string
    title?: string
    views?: number
    clicks?: number
    count?: number
}

interface Props {
    title: string
    items: Item[]
    icon: any
    iconBgClass?: string
    iconClass?: string
    valueLabel?: string
    emptyText?: string
    loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    iconBgClass: 'bg-primary-100',
    iconClass: 'text-primary-600',
    valueLabel: 'views',
    emptyText: 'No data yet',
    loading: false,
})

const maxValue = computed(() => {
    if (props.items.length === 0) return 0
    return Math.max(...props.items.map(item => getValue(item)))
})

const getValue = (item: Item): number => {
    return item.views ?? item.clicks ?? item.count ?? 0
}

const getLabel = (item: Item): string => {
    return item.name ?? item.title ?? 'Unknown'
}

const getPercentage = (item: Item): number => {
    if (maxValue.value === 0) return 0
    return (getValue(item) / maxValue.value) * 100
}
</script>

<template>
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-4">
            <div :class="['w-10 h-10 rounded-xl flex items-center justify-center', iconBgClass]">
                <component :is="icon" :class="['w-5 h-5', iconClass]" />
            </div>
            <h3 class="font-semibold text-gray-900">{{ title }}</h3>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="space-y-3">
            <div v-for="i in 3" :key="i" class="flex items-center gap-3">
                <div class="flex-1">
                    <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                    <div class="h-2 bg-gray-100 rounded w-full mt-2 animate-pulse"></div>
                </div>
                <div class="h-4 w-12 bg-gray-200 rounded animate-pulse"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="items.length === 0" class="text-center py-8">
            <component :is="icon" class="w-10 h-10 text-gray-300 mx-auto mb-2" />
            <p class="text-sm text-gray-500">{{ emptyText }}</p>
        </div>

        <!-- Items List -->
        <div v-else class="space-y-4">
            <div
                v-for="(item, index) in items"
                :key="item.id"
                class="group"
            >
                <div class="flex items-center justify-between mb-1">
                    <div class="flex items-center gap-2 min-w-0">
                        <span class="text-sm font-medium text-gray-400 w-5">
                            {{ index + 1 }}.
                        </span>
                        <span class="text-sm font-medium text-gray-700 truncate">
                            {{ getLabel(item) }}
                        </span>
                    </div>
                    <span class="text-sm font-semibold text-gray-900 ml-2">
                        {{ getValue(item).toLocaleString() }}
                    </span>
                </div>
                <div class="ml-7 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div
                        class="h-full rounded-full transition-all duration-500 ease-out"
                        :class="index === 0 ? 'bg-primary-500' : 'bg-primary-300'"
                        :style="{ width: `${getPercentage(item)}%` }"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>

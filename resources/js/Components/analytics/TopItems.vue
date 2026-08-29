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
    <div class="h-full rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center gap-3">
            <div :class="['flex h-10 w-10 items-center justify-center rounded-xl', iconBgClass]">
                <component :is="icon" :class="['h-5 w-5', iconClass]" />
            </div>
            <h3 class="text-base font-semibold text-slate-900">{{ title }}</h3>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="space-y-4">
            <div v-for="i in 3" :key="i" class="flex items-center gap-3">
                <div class="flex-1">
                    <div class="h-4 w-3/4 animate-pulse rounded bg-slate-200"></div>
                    <div class="mt-2 h-2 w-full animate-pulse rounded bg-slate-100"></div>
                </div>
                <div class="h-4 w-12 animate-pulse rounded bg-slate-200"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="items.length === 0" class="flex flex-col items-center justify-center py-10 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50">
                <component :is="icon" class="h-6 w-6 text-slate-300" />
            </div>
            <p class="text-sm text-slate-400">{{ emptyText }}</p>
        </div>

        <!-- Items List -->
        <div v-else class="space-y-4">
            <div v-for="(item, index) in items" :key="item.id" class="group">
                <div class="mb-1.5 flex items-center justify-between gap-2">
                    <div class="flex min-w-0 items-center gap-2.5">
                        <span
                            :class="[
                                'flex h-5 w-5 flex-shrink-0 items-center justify-center rounded-md text-xs font-bold',
                                index === 0
                                    ? 'bg-indigo-100 text-indigo-700'
                                    : 'bg-slate-100 text-slate-500',
                            ]"
                        >
                            {{ index + 1 }}
                        </span>
                        <span class="truncate text-sm font-medium text-slate-700">
                            {{ getLabel(item) }}
                        </span>
                    </div>
                    <span class="ml-2 text-sm font-semibold text-slate-900">
                        {{ getValue(item).toLocaleString() }}
                    </span>
                </div>
                <div class="ml-7 h-1.5 overflow-hidden rounded-full bg-slate-100">
                    <div
                        class="h-full rounded-full bg-gradient-to-r transition-all duration-500 ease-out"
                        :class="index === 0 ? 'from-indigo-500 to-purple-500' : 'from-slate-300 to-slate-300'"
                        :style="{ width: `${getPercentage(item)}%` }"
                    ></div>
                </div>
            </div>
        </div>
    </div>
</template>

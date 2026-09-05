<script setup lang="ts">
import { computed } from 'vue'
import { Eye, MousePointer, MessageCircle, ShoppingBag, Activity } from 'lucide-vue-next'

interface ActivityItem {
    type: 'page_view' | 'product_view' | 'link_click' | 'whatsapp_click'
    description: string
    created_at: string
}

interface Props {
    activities: ActivityItem[]
    loading?: boolean
}

const props = withDefaults(defineProps<Props>(), {
    loading: false,
})

const getIcon = (type: string) => {
    const icons: Record<string, any> = {
        page_view: Eye,
        product_view: ShoppingBag,
        link_click: MousePointer,
        whatsapp_click: MessageCircle,
    }
    return icons[type] || Activity
}

const getIconClass = (type: string) => {
    const classes: Record<string, { bg: string; text: string }> = {
        page_view: { bg: 'accent-tint', text: 'accent-text' },
        product_view: { bg: 'bg-purple-100', text: 'text-purple-600' },
        link_click: { bg: 'bg-green-100', text: 'text-green-600' },
        whatsapp_click: { bg: 'bg-emerald-100', text: 'text-emerald-600' },
    }
    return classes[type] || { bg: 'bg-gray-100', text: 'text-gray-600' }
}

const formatTime = (dateString: string) => {
    const date = new Date(dateString)
    const now = new Date()
    const diffInSeconds = Math.floor((now.getTime() - date.getTime()) / 1000)

    if (diffInSeconds < 60) return 'Just now'
    if (diffInSeconds < 3600) return `${Math.floor(diffInSeconds / 60)}m ago`
    if (diffInSeconds < 86400) return `${Math.floor(diffInSeconds / 3600)}h ago`
    if (diffInSeconds < 604800) return `${Math.floor(diffInSeconds / 86400)}d ago`

    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' })
}
</script>

<template>
    <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
        <div class="mb-5 flex items-center gap-3">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl bg-slate-100">
                <Activity class="h-5 w-5 text-slate-600" />
            </div>
            <h3 class="text-base font-semibold text-slate-900">Recent Activity</h3>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="space-y-2">
            <div v-for="i in 5" :key="i" class="flex items-center gap-3 py-1">
                <div class="h-9 w-9 animate-pulse rounded-lg bg-slate-200"></div>
                <div class="flex-1">
                    <div class="h-4 w-3/4 animate-pulse rounded bg-slate-200"></div>
                </div>
                <div class="h-3 w-12 animate-pulse rounded bg-slate-100"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="activities.length === 0" class="flex flex-col items-center justify-center py-10 text-center">
            <div class="mb-3 flex h-12 w-12 items-center justify-center rounded-2xl bg-slate-50">
                <Activity class="h-6 w-6 text-slate-300" />
            </div>
            <p class="text-sm text-slate-400">No activity yet</p>
        </div>

        <!-- Activity List -->
        <div v-else class="-mx-2 divide-y divide-line">
            <div
                v-for="(activity, index) in activities"
                :key="index"
                class="flex items-center gap-3 rounded-xl px-2 py-2.5 transition-colors hover:bg-slate-50"
            >
                <div
                    :class="[
                        'flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-lg',
                        getIconClass(activity.type).bg,
                    ]"
                >
                    <component :is="getIcon(activity.type)" :class="['h-4 w-4', getIconClass(activity.type).text]" />
                </div>
                <p class="flex-1 truncate text-sm text-slate-600">{{ activity.description }}</p>
                <span class="whitespace-nowrap text-xs font-medium text-slate-400">
                    {{ formatTime(activity.created_at) }}
                </span>
            </div>
        </div>
    </div>
</template>

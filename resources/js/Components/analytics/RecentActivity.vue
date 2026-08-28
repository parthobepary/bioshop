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
        page_view: { bg: 'bg-blue-100', text: 'text-blue-600' },
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
    <div class="bg-white rounded-2xl border border-gray-100 p-5">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 rounded-xl flex items-center justify-center bg-gray-100">
                <Activity class="w-5 h-5 text-gray-600" />
            </div>
            <h3 class="font-semibold text-gray-900">Recent Activity</h3>
        </div>

        <!-- Loading State -->
        <div v-if="loading" class="space-y-3">
            <div v-for="i in 5" :key="i" class="flex items-center gap-3">
                <div class="w-8 h-8 bg-gray-200 rounded-lg animate-pulse"></div>
                <div class="flex-1">
                    <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                </div>
                <div class="h-3 w-12 bg-gray-100 rounded animate-pulse"></div>
            </div>
        </div>

        <!-- Empty State -->
        <div v-else-if="activities.length === 0" class="text-center py-8">
            <Activity class="w-10 h-10 text-gray-300 mx-auto mb-2" />
            <p class="text-sm text-gray-500">No activity yet</p>
        </div>

        <!-- Activity List -->
        <div v-else class="space-y-3">
            <div
                v-for="(activity, index) in activities"
                :key="index"
                class="flex items-center gap-3"
            >
                <div
                    :class="[
                        'w-8 h-8 rounded-lg flex items-center justify-center flex-shrink-0',
                        getIconClass(activity.type).bg
                    ]"
                >
                    <component
                        :is="getIcon(activity.type)"
                        :class="['w-4 h-4', getIconClass(activity.type).text]"
                    />
                </div>
                <p class="flex-1 text-sm text-gray-600 truncate">
                    {{ activity.description }}
                </p>
                <span class="text-xs text-gray-400 whitespace-nowrap">
                    {{ formatTime(activity.created_at) }}
                </span>
            </div>
        </div>
    </div>
</template>

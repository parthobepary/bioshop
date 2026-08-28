<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
import { Button } from '@/Components/ui/button'
import StatsCard from '@/Components/analytics/StatsCard.vue'
import ViewsChart from '@/Components/analytics/ViewsChart.vue'
import TopItems from '@/Components/analytics/TopItems.vue'
import RecentActivity from '@/Components/analytics/RecentActivity.vue'
import {
    Eye,
    MousePointer,
    ShoppingBag,
    MessageCircle,
    Link2,
    Globe,
    TrendingUp,
    Calendar,
    ArrowLeft,
} from 'lucide-vue-next'

interface Stats {
    page_views: { total: number; today: number; this_week: number; this_month: number }
    link_clicks: { total: number; today: number; this_week: number; this_month: number }
    product_views: { total: number; today: number; this_week: number; this_month: number }
    whatsapp_clicks: { total: number; today: number; this_week: number; this_month: number }
}

interface ChartData {
    labels: string[]
    data: number[]
}

interface TopProduct {
    id: number
    name: string
    views: number
}

interface TopLink {
    id: number
    title: string
    clicks: number
}

interface TrafficSource {
    source: string
    count: number
}

interface ActivityItem {
    type: 'page_view' | 'product_view' | 'link_click' | 'whatsapp_click'
    description: string
    created_at: string
}

interface Props {
    stats: Stats
    chartData: ChartData
    topProducts: TopProduct[]
    topLinks: TopLink[]
    trafficSources: TrafficSource[]
    recentActivity: ActivityItem[]
    selectedDays: number
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const timeRanges = [
    { value: 7, label: '7 days' },
    { value: 14, label: '14 days' },
    { value: 30, label: '30 days' },
    { value: 90, label: '90 days' },
]

const changeTimeRange = (days: number) => {
    router.get('/dashboard/analytics', { days }, {
        preserveState: true,
        preserveScroll: true,
    })
}

const getChangePercentage = (current: number, previous: number): number | null => {
    if (previous === 0) return current > 0 ? 100 : null
    return Math.round(((current - previous) / previous) * 100)
}
</script>

<template>
    <Head title="Analytics" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <div class="flex items-center gap-3 mb-2">
                    <Link
                        href="/dashboard"
                        class="p-2 hover:bg-gray-100 rounded-lg transition-colors"
                    >
                        <ArrowLeft class="w-5 h-5 text-gray-500" />
                    </Link>
                    <h1 class="text-2xl font-bold text-gray-900">Analytics</h1>
                </div>
                <p class="text-gray-500">Track your shop's performance and visitor engagement</p>
            </div>

            <!-- Time Range Selector -->
            <div class="flex items-center gap-2 bg-gray-100 p-1 rounded-xl">
                <button
                    v-for="range in timeRanges"
                    :key="range.value"
                    :class="[
                        'px-4 py-2 rounded-lg text-sm font-medium transition-all',
                        selectedDays === range.value
                            ? 'bg-white shadow text-gray-900'
                            : 'text-gray-600 hover:text-gray-900'
                    ]"
                    @click="changeTimeRange(range.value)"
                >
                    {{ range.label }}
                </button>
            </div>
        </div>

        <!-- Overview Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <StatsCard
                title="Total Page Views"
                :value="stats.page_views.total"
                :icon="Eye"
                icon-bg-class="bg-blue-100"
                icon-class="text-blue-600"
            />
            <StatsCard
                title="Link Clicks"
                :value="stats.link_clicks.total"
                :icon="MousePointer"
                icon-bg-class="bg-green-100"
                icon-class="text-green-600"
            />
            <StatsCard
                title="Product Views"
                :value="stats.product_views.total"
                :icon="ShoppingBag"
                icon-bg-class="bg-purple-100"
                icon-class="text-purple-600"
            />
            <StatsCard
                title="WhatsApp Inquiries"
                :value="stats.whatsapp_clicks.total"
                :icon="MessageCircle"
                icon-bg-class="bg-emerald-100"
                icon-class="text-emerald-600"
            />
        </div>

        <!-- Period Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
            <Card>
                <CardContent class="p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                            <Calendar class="w-5 h-5 text-amber-600" />
                        </div>
                        <span class="font-medium text-gray-900">Today</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.page_views.today }}</p>
                            <p class="text-sm text-gray-500">Page views</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.whatsapp_clicks.today }}</p>
                            <p class="text-sm text-gray-500">WhatsApp</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <Card>
                <CardContent class="p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                            <TrendingUp class="w-5 h-5 text-blue-600" />
                        </div>
                        <span class="font-medium text-gray-900">This Week</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.page_views.this_week }}</p>
                            <p class="text-sm text-gray-500">Page views</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.whatsapp_clicks.this_week }}</p>
                            <p class="text-sm text-gray-500">WhatsApp</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
            <Card>
                <CardContent class="p-5">
                    <div class="flex items-center gap-3 mb-4">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                            <Calendar class="w-5 h-5 text-green-600" />
                        </div>
                        <span class="font-medium text-gray-900">This Month</span>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.page_views.this_month }}</p>
                            <p class="text-sm text-gray-500">Page views</p>
                        </div>
                        <div>
                            <p class="text-2xl font-bold text-gray-900">{{ stats.whatsapp_clicks.this_month }}</p>
                            <p class="text-sm text-gray-500">WhatsApp</p>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Chart -->
        <ViewsChart
            :labels="chartData.labels"
            :data="chartData.data"
            :title="`Page Views (Last ${selectedDays} days)`"
        />

        <!-- Top Items Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <TopItems
                title="Top Products"
                :items="topProducts"
                :icon="ShoppingBag"
                icon-bg-class="bg-purple-100"
                icon-class="text-purple-600"
                value-label="views"
                empty-text="No product views yet"
            />
            <TopItems
                title="Top Links"
                :items="topLinks"
                :icon="Link2"
                icon-bg-class="bg-green-100"
                icon-class="text-green-600"
                value-label="clicks"
                empty-text="No link clicks yet"
            />
            <TopItems
                title="Traffic Sources"
                :items="trafficSources.map((s, i) => ({ id: i, name: s.source, count: s.count }))"
                :icon="Globe"
                icon-bg-class="bg-blue-100"
                icon-class="text-blue-600"
                value-label="visits"
                empty-text="No referrers tracked"
            />
        </div>

        <!-- Recent Activity -->
        <RecentActivity :activities="recentActivity" />
    </div>
</template>

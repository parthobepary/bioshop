<script setup lang="ts">
import { ref, computed } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
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

const periods = computed(() => [
    {
        label: 'Today',
        icon: Calendar,
        iconBg: 'bg-amber-100',
        iconText: 'text-amber-600',
        views: props.stats.page_views.today,
        whatsapp: props.stats.whatsapp_clicks.today,
    },
    {
        label: 'This Week',
        icon: TrendingUp,
        iconBg: 'bg-blue-100',
        iconText: 'text-blue-600',
        views: props.stats.page_views.this_week,
        whatsapp: props.stats.whatsapp_clicks.this_week,
    },
    {
        label: 'This Month',
        icon: Calendar,
        iconBg: 'bg-emerald-100',
        iconText: 'text-emerald-600',
        views: props.stats.page_views.this_month,
        whatsapp: props.stats.whatsapp_clicks.this_month,
    },
])
</script>

<template>
    <Head title="Analytics" />

    <div class="mx-auto max-w-7xl space-y-8">
        <!-- Header -->
        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-start gap-3">
                <Link
                    href="/dashboard"
                    class="mt-0.5 flex h-9 w-9 items-center justify-center rounded-xl border border-slate-200 bg-white text-slate-500 shadow-sm transition-colors hover:bg-slate-50 hover:text-slate-700"
                >
                    <ArrowLeft class="h-5 w-5" />
                </Link>
                <div>
                    <h1 class="text-2xl font-bold tracking-tight text-slate-900">Analytics</h1>
                    <p class="mt-0.5 text-sm text-slate-500">
                        Track your shop's performance and visitor engagement
                    </p>
                </div>
            </div>

            <!-- Time Range Selector -->
            <div class="inline-flex items-center gap-1 self-start rounded-xl border border-slate-200 bg-slate-100/70 p-1 sm:self-auto">
                <button
                    v-for="range in timeRanges"
                    :key="range.value"
                    :class="[
                        'rounded-lg px-3.5 py-1.5 text-sm font-medium transition-all duration-200',
                        Number(selectedDays) === range.value
                            ? 'bg-white text-slate-900 shadow-sm ring-1 ring-slate-200/60'
                            : 'text-slate-500 hover:text-slate-800',
                    ]"
                    @click="changeTimeRange(range.value)"
                >
                    {{ range.label }}
                </button>
            </div>
        </div>

        <!-- Overview Stats -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <StatsCard
                title="Total Page Views"
                :value="stats.page_views.total"
                :icon="Eye"
                accent="from-blue-500 to-indigo-500"
            />
            <StatsCard
                title="Link Clicks"
                :value="stats.link_clicks.total"
                :icon="MousePointer"
                accent="from-emerald-500 to-teal-500"
            />
            <StatsCard
                title="Product Views"
                :value="stats.product_views.total"
                :icon="ShoppingBag"
                accent="from-purple-500 to-fuchsia-500"
            />
            <StatsCard
                title="WhatsApp Inquiries"
                :value="stats.whatsapp_clicks.total"
                :icon="MessageCircle"
                accent="from-green-500 to-emerald-500"
            />
        </div>

        <!-- Chart + At a glance -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
            <div class="lg:col-span-2">
                <ViewsChart
                    :labels="chartData.labels"
                    :data="chartData.data"
                    :title="`Page Views · Last ${selectedDays} days`"
                />
            </div>

            <!-- At a glance -->
            <div class="rounded-2xl border border-slate-200/70 bg-white p-6 shadow-sm">
                <h3 class="text-base font-semibold text-slate-900">At a glance</h3>
                <p class="mt-1 text-sm text-slate-500">Page views &amp; WhatsApp inquiries</p>

                <div class="mt-5 space-y-3">
                    <div
                        v-for="period in periods"
                        :key="period.label"
                        class="flex items-center gap-4 rounded-xl border border-slate-100 bg-slate-50/60 p-3.5"
                    >
                        <div
                            :class="[
                                'flex h-10 w-10 flex-shrink-0 items-center justify-center rounded-xl',
                                period.iconBg,
                            ]"
                        >
                            <component :is="period.icon" :class="['h-5 w-5', period.iconText]" />
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-sm font-medium text-slate-700">{{ period.label }}</p>
                        </div>
                        <div class="flex items-center gap-5 text-right">
                            <div>
                                <p class="text-lg font-bold leading-none text-slate-900">{{ period.views }}</p>
                                <p class="mt-1 text-[11px] uppercase tracking-wide text-slate-400">Views</p>
                            </div>
                            <div>
                                <p class="text-lg font-bold leading-none text-slate-900">{{ period.whatsapp }}</p>
                                <p class="mt-1 text-[11px] uppercase tracking-wide text-slate-400">Chat</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Items Grid -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
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
                icon-bg-class="bg-emerald-100"
                icon-class="text-emerald-600"
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

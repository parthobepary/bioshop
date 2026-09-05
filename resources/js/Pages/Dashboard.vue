<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { mediaUrl } from '@/lib/media'
import StatsCard from '@/Components/analytics/StatsCard.vue'
import ViewsChart from '@/Components/analytics/ViewsChart.vue'
import TopItems from '@/Components/analytics/TopItems.vue'
import RecentActivity from '@/Components/analytics/RecentActivity.vue'
import {
    Eye,
    MousePointer,
    ShoppingBag,
    MessageCircle,
    Plus,
    ExternalLink,
    TrendingUp,
    BarChart3,
} from 'lucide-vue-next'

interface Profile {
    id: number
    username: string
    name: string
    photo: string | null
}

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

interface ActivityItem {
    type: 'page_view' | 'product_view' | 'link_click' | 'whatsapp_click'
    description: string
    created_at: string
}

interface Props {
    profile: Profile
    stats: Stats
    chartData: ChartData
    topProducts: TopProduct[]
    recentActivity: ActivityItem[]
}

const props = defineProps<Props>()

defineOptions({
    layout: DashboardLayout,
})

const quickActions = [
    { name: 'Add Link', href: '/dashboard/links', icon: Plus },
    { name: 'Add Product', href: '/dashboard/products/create', icon: Plus },
    { name: 'View Shop', href: `/${props.profile?.username}`, icon: ExternalLink, external: true },
]

const hasActivity = computed(() => {
    return props.stats.page_views.total > 0 ||
           props.stats.link_clicks.total > 0 ||
           props.stats.product_views.total > 0
})
</script>

<template>
    <Head title="Dashboard" />

    <div class="mx-auto max-w-7xl space-y-6">
        <!-- Welcome Section -->
        <div class="accent-bg relative overflow-hidden rounded-xl p-5 sm:p-6">
            <div class="relative flex items-center gap-3.5">
                <div class="on-accent-surface flex h-12 w-12 items-center justify-center overflow-hidden rounded-xl border">
                    <img
                        v-if="profile?.photo"
                        :src="mediaUrl(profile.photo)"
                        :alt="profile.name"
                        class="h-full w-full object-cover"
                    />
                    <span v-else class="text-base font-semibold">
                        {{ profile?.name?.charAt(0) || 'S' }}
                    </span>
                </div>
                <div>
                    <h1 class="font-display text-lg font-semibold tracking-tight">Welcome back, {{ profile?.name }}!</h1>
                    <p class="on-accent-muted mt-0.5 text-[13px]">Here's what's happening with your shop today.</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="relative mt-5 flex flex-wrap gap-2">
                <template v-for="action in quickActions" :key="action.name">
                    <a
                        v-if="action.external"
                        :href="action.href"
                        target="_blank"
                        class="on-accent-surface inline-flex items-center gap-2 rounded-lg border px-3 py-1.5 text-[13px] font-medium"
                    >
                        <component :is="action.icon" class="h-4 w-4" />
                        {{ action.name }}
                    </a>
                    <Link
                        v-else
                        :href="action.href"
                        class="on-accent-surface inline-flex items-center gap-2 rounded-lg border px-3 py-1.5 text-[13px] font-medium"
                    >
                        <component :is="action.icon" class="h-4 w-4" />
                        {{ action.name }}
                    </Link>
                </template>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-4">
            <StatsCard
                title="Page Views"
                :value="stats.page_views.total"
                :icon="Eye"
                tone="accent"
            />
            <StatsCard
                title="Link Clicks"
                :value="stats.link_clicks.total"
                :icon="MousePointer"
                tone="success"
            />
            <StatsCard
                title="Product Views"
                :value="stats.product_views.total"
                :icon="ShoppingBag"
                tone="ink"
            />
            <StatsCard
                title="WhatsApp Clicks"
                :value="stats.whatsapp_clicks.total"
                :icon="MessageCircle"
                tone="warning"
            />
        </div>

        <!-- Charts and Activity -->
        <div v-if="hasActivity" class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <!-- Views Chart -->
            <div class="lg:col-span-2">
                <ViewsChart
                    :labels="chartData.labels"
                    :data="chartData.data"
                    title="Page Views (Last 7 days)"
                >
                    <template #actions>
                        <Link
                            href="/dashboard/analytics"
                            class="flex items-center gap-1 text-sm font-medium accent-text "
                        >
                            <BarChart3 class="w-4 h-4" />
                            View All
                        </Link>
                    </template>
                </ViewsChart>
            </div>

            <!-- Top Products -->
            <TopItems
                title="Top Products"
                :items="topProducts"
                :icon="ShoppingBag"
                icon-bg-class="accent-tint"
                icon-class="accent-text"
                value-label="views"
                empty-text="No product views yet"
            />
        </div>

        <!-- Recent Activity -->
        <div v-if="hasActivity" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <RecentActivity :activities="recentActivity" />

            <!-- Quick Stats for Today -->
            <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
                <h3 class="mb-5 flex items-center gap-2 text-base font-semibold text-slate-900">
                    <TrendingUp class="h-5 w-5 accent-text" />
                    Today's Summary
                </h3>
                <div class="divide-y divide-line">
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg accent-tint">
                                <Eye class="h-4 w-4 accent-text" />
                            </div>
                            <span class="text-slate-600">Page Views</span>
                        </div>
                        <span class="font-semibold text-slate-900">{{ stats.page_views.today }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-emerald-100">
                                <MousePointer class="h-4 w-4 text-emerald-600" />
                            </div>
                            <span class="text-slate-600">Link Clicks</span>
                        </div>
                        <span class="font-semibold text-slate-900">{{ stats.link_clicks.today }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-purple-100">
                                <ShoppingBag class="h-4 w-4 text-purple-600" />
                            </div>
                            <span class="text-slate-600">Product Views</span>
                        </div>
                        <span class="font-semibold text-slate-900">{{ stats.product_views.today }}</span>
                    </div>
                    <div class="flex items-center justify-between py-3">
                        <div class="flex items-center gap-3">
                            <div class="flex h-8 w-8 items-center justify-center rounded-lg bg-green-100">
                                <MessageCircle class="h-4 w-4 text-green-600" />
                            </div>
                            <span class="text-slate-600">WhatsApp Clicks</span>
                        </div>
                        <span class="font-semibold text-slate-900">{{ stats.whatsapp_clicks.today }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Getting Started Section (only show if no activity) -->
        <div v-if="!hasActivity" class="rounded-2xl border border-line bg-white p-6 shadow-sm">
            <h3 class="text-base font-semibold text-slate-900">Getting Started</h3>
            <p class="mt-1 text-sm text-slate-500">Complete these steps to launch your shop.</p>
            <div class="mt-5 grid grid-cols-1 gap-4 md:grid-cols-3">
                <div class="group rounded-2xl border border-line bg-slate-50/60 p-5 transition-all hover-accent-border hover:bg-white hover:shadow-md">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-ink-900 font-bold text-white shadow-sm">
                        1
                    </div>
                    <h4 class="font-semibold text-slate-900">Add Your Links</h4>
                    <p class="mt-1 text-sm text-slate-500">Add social media and other important links to your profile.</p>
                    <Link href="/dashboard/links" class="mt-3 inline-flex items-center gap-1 text-sm font-medium accent-text ">
                        Add Links →
                    </Link>
                </div>
                <div class="group rounded-2xl border border-line bg-slate-50/60 p-5 transition-all hover-accent-border hover:bg-white hover:shadow-md">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-ink-900 font-bold text-white shadow-sm">
                        2
                    </div>
                    <h4 class="font-semibold text-slate-900">Add Products</h4>
                    <p class="mt-1 text-sm text-slate-500">Showcase your products with images and prices.</p>
                    <Link href="/dashboard/products" class="mt-3 inline-flex items-center gap-1 text-sm font-medium accent-text ">
                        Add Products →
                    </Link>
                </div>
                <div class="group rounded-2xl border border-line bg-slate-50/60 p-5 transition-all hover-accent-border hover:bg-white hover:shadow-md">
                    <div class="mb-3 flex h-10 w-10 items-center justify-center rounded-xl bg-success-600 font-bold text-white shadow-sm">
                        3
                    </div>
                    <h4 class="font-semibold text-slate-900">Setup Payment</h4>
                    <p class="mt-1 text-sm text-slate-500">Add your bKash, Nagad or bank details for customers.</p>
                    <Link href="/dashboard/payment" class="mt-3 inline-flex items-center gap-1 text-sm font-medium accent-text ">
                        Setup Payment →
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

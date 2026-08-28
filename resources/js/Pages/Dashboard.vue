<script setup lang="ts">
import { computed } from 'vue'
import { Head, Link } from '@inertiajs/vue3'
import DashboardLayout from '@/Layouts/DashboardLayout.vue'
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
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

    <div class="space-y-6">
        <!-- Welcome Section -->
        <div class="bg-gradient-to-r from-primary-500 to-purple-600 rounded-2xl p-6 text-white">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center overflow-hidden">
                    <img
                        v-if="profile?.photo"
                        :src="`/storage/${profile.photo}`"
                        :alt="profile.name"
                        class="w-full h-full object-cover"
                    />
                    <span v-else class="text-2xl font-bold">
                        {{ profile?.name?.charAt(0) || 'S' }}
                    </span>
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Welcome back, {{ profile?.name }}!</h1>
                    <p class="text-primary-100 mt-1">Here's what's happening with your shop today.</p>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="flex flex-wrap gap-3 mt-6">
                <template v-for="action in quickActions" :key="action.name">
                    <a
                        v-if="action.external"
                        :href="action.href"
                        target="_blank"
                        class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-xl text-sm font-medium transition-colors"
                    >
                        <component :is="action.icon" class="w-4 h-4" />
                        {{ action.name }}
                    </a>
                    <Link
                        v-else
                        :href="action.href"
                        class="flex items-center gap-2 px-4 py-2 bg-white/20 hover:bg-white/30 rounded-xl text-sm font-medium transition-colors"
                    >
                        <component :is="action.icon" class="w-4 h-4" />
                        {{ action.name }}
                    </Link>
                </template>
            </div>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <StatsCard
                title="Page Views"
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
                title="WhatsApp Clicks"
                :value="stats.whatsapp_clicks.total"
                :icon="MessageCircle"
                icon-bg-class="bg-emerald-100"
                icon-class="text-emerald-600"
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
                            class="text-sm text-primary-600 hover:text-primary-700 font-medium flex items-center gap-1"
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
                icon-bg-class="bg-purple-100"
                icon-class="text-purple-600"
                value-label="views"
                empty-text="No product views yet"
            />
        </div>

        <!-- Recent Activity -->
        <div v-if="hasActivity" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <RecentActivity :activities="recentActivity" />

            <!-- Quick Stats for Today -->
            <Card>
                <CardHeader>
                    <CardTitle class="flex items-center gap-2">
                        <TrendingUp class="w-5 h-5 text-primary-600" />
                        Today's Summary
                    </CardTitle>
                </CardHeader>
                <CardContent>
                    <div class="space-y-4">
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-blue-100 rounded-lg flex items-center justify-center">
                                    <Eye class="w-4 h-4 text-blue-600" />
                                </div>
                                <span class="text-gray-600">Page Views</span>
                            </div>
                            <span class="font-semibold text-gray-900">{{ stats.page_views.today }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                                    <MousePointer class="w-4 h-4 text-green-600" />
                                </div>
                                <span class="text-gray-600">Link Clicks</span>
                            </div>
                            <span class="font-semibold text-gray-900">{{ stats.link_clicks.today }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2 border-b border-gray-100">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-purple-100 rounded-lg flex items-center justify-center">
                                    <ShoppingBag class="w-4 h-4 text-purple-600" />
                                </div>
                                <span class="text-gray-600">Product Views</span>
                            </div>
                            <span class="font-semibold text-gray-900">{{ stats.product_views.today }}</span>
                        </div>
                        <div class="flex items-center justify-between py-2">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center">
                                    <MessageCircle class="w-4 h-4 text-emerald-600" />
                                </div>
                                <span class="text-gray-600">WhatsApp Clicks</span>
                            </div>
                            <span class="font-semibold text-gray-900">{{ stats.whatsapp_clicks.today }}</span>
                        </div>
                    </div>
                </CardContent>
            </Card>
        </div>

        <!-- Getting Started Section (only show if no activity) -->
        <Card v-if="!hasActivity">
            <CardHeader>
                <CardTitle>Getting Started</CardTitle>
            </CardHeader>
            <CardContent>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mb-3">
                            <span class="font-bold">1</span>
                        </div>
                        <h3 class="font-medium text-gray-900">Add Your Links</h3>
                        <p class="text-sm text-gray-500 mt-1">Add social media and other important links to your profile.</p>
                        <Link href="/dashboard/links" class="text-primary-600 text-sm font-medium mt-2 inline-block hover:underline">
                            Add Links →
                        </Link>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mb-3">
                            <span class="font-bold">2</span>
                        </div>
                        <h3 class="font-medium text-gray-900">Add Products</h3>
                        <p class="text-sm text-gray-500 mt-1">Showcase your products with images and prices.</p>
                        <Link href="/dashboard/products" class="text-primary-600 text-sm font-medium mt-2 inline-block hover:underline">
                            Add Products →
                        </Link>
                    </div>
                    <div class="p-4 bg-gray-50 rounded-xl">
                        <div class="w-10 h-10 bg-primary-100 text-primary-600 rounded-lg flex items-center justify-center mb-3">
                            <span class="font-bold">3</span>
                        </div>
                        <h3 class="font-medium text-gray-900">Setup Payment</h3>
                        <p class="text-sm text-gray-500 mt-1">Add your bKash, Nagad or bank details for customers.</p>
                        <Link href="/dashboard/payment" class="text-primary-600 text-sm font-medium mt-2 inline-block hover:underline">
                            Setup Payment →
                        </Link>
                    </div>
                </div>
            </CardContent>
        </Card>
    </div>
</template>

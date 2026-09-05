<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Line } from 'vue-chartjs'
import {
    Chart as ChartJS,
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Filler,
} from 'chart.js'
import {
    Users,
    CreditCard,
    DollarSign,
    Eye,
    TrendingUp,
    Clock,
    ShoppingBag,
    UserPlus,
} from 'lucide-vue-next'

ChartJS.register(
    CategoryScale,
    LinearScale,
    PointElement,
    LineElement,
    Title,
    Tooltip,
    Filler
)

interface Stats {
    total_users: number
    new_users_today: number
    new_users_this_month: number
    total_profiles: number
    total_products: number
    active_subscriptions: number
    total_revenue: number
    revenue_this_month: number
    pending_payments: number
    total_page_views: number
    page_views_today: number
}

interface RecentUser {
    id: number
    name: string
    email: string
    username: string | null
    created_at: string
}

interface Payment {
    id: number
    amount: number
    method: string
    transaction_id: string
    status: string
    created_at: string
    user: { id: number; name: string; email: string }
    subscription: { plan: { name: string } } | null
}

interface ChartData {
    labels: string[]
    data: number[]
}

interface Props {
    stats: Stats
    recentUsers: RecentUser[]
    pendingPayments: Payment[]
    userGrowth: ChartData
    revenueChart: ChartData
}

const props = defineProps<Props>()

defineOptions({
    layout: AdminLayout,
})

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
    }).format(price).replace('BDT', '৳')
}

const formatDate = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    })
}

const userChartData = {
    labels: props.userGrowth.labels,
    datasets: [{
        label: 'New Users',
        data: props.userGrowth.data,
        fill: true,
        borderColor: '#f97316',
        backgroundColor: 'rgba(249, 115, 22, 0.1)',
        tension: 0.4,
        pointRadius: 0,
    }],
}

const revenueChartData = {
    labels: props.revenueChart.labels,
    datasets: [{
        label: 'Revenue',
        data: props.revenueChart.data,
        fill: true,
        borderColor: '#22c55e',
        backgroundColor: 'rgba(34, 197, 94, 0.1)',
        tension: 0.4,
        pointRadius: 0,
    }],
}

const chartOptions = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: { display: false },
    },
    scales: {
        x: {
            grid: { display: false, color: 'rgba(255,255,255,0.1)' },
            ticks: { color: '#64748b', maxTicksLimit: 7 },
            border: { display: false },
        },
        y: {
            beginAtZero: true,
            grid: { color: 'rgba(255,255,255,0.05)' },
            ticks: { color: '#64748b', precision: 0 },
            border: { display: false },
        },
    },
}
</script>

<template>
    <Head title="Admin Dashboard" />

    <div class="space-y-6">
        <!-- Header -->
        <div>
            <h1 class="text-xl font-semibold text-ink-900">Dashboard</h1>
            <p class="text-ink-500 mt-1">Platform overview and statistics</p>
        </div>

        <!-- Stats Grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-white rounded-2xl p-5 border border-line">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-ink-500 text-sm">Total Users</p>
                        <p class="text-xl font-semibold text-ink-900 mt-1">{{ stats.total_users.toLocaleString() }}</p>
                        <p class="text-xs text-success-600 mt-1">+{{ stats.new_users_today }} today</p>
                    </div>
                    <div class="w-12 h-12 bg-ink-900/20 rounded-xl flex items-center justify-center">
                        <Users class="w-6 h-6 text-accent-600" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-line">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-ink-500 text-sm">Active Subscriptions</p>
                        <p class="text-xl font-semibold text-ink-900 mt-1">{{ stats.active_subscriptions.toLocaleString() }}</p>
                        <p class="text-xs text-slate-500 mt-1">Paid users</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-500/20 rounded-xl flex items-center justify-center">
                        <CreditCard class="w-6 h-6 text-purple-400" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-line">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-ink-500 text-sm">Total Revenue</p>
                        <p class="text-xl font-semibold text-ink-900 mt-1">{{ formatPrice(stats.total_revenue) }}</p>
                        <p class="text-xs text-success-600 mt-1">{{ formatPrice(stats.revenue_this_month) }} this month</p>
                    </div>
                    <div class="w-12 h-12 bg-success-500/20 rounded-xl flex items-center justify-center">
                        <DollarSign class="w-6 h-6 text-success-600" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-line">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-ink-500 text-sm">Total Page Views</p>
                        <p class="text-xl font-semibold text-ink-900 mt-1">{{ stats.total_page_views.toLocaleString() }}</p>
                        <p class="text-xs text-accent-600 mt-1">{{ stats.page_views_today }} today</p>
                    </div>
                    <div class="w-12 h-12 bg-accent-600/20 rounded-xl flex items-center justify-center">
                        <Eye class="w-6 h-6 text-accent-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white rounded-2xl p-5 border border-line">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-ink-900">User Growth</h3>
                        <p class="text-sm text-ink-500">Last 30 days</p>
                    </div>
                    <UserPlus class="w-5 h-5 text-accent-600" />
                </div>
                <div class="h-[200px]">
                    <Line :data="userChartData" :options="chartOptions" />
                </div>
            </div>

            <div class="bg-white rounded-2xl p-5 border border-line">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="font-semibold text-ink-900">Revenue</h3>
                        <p class="text-sm text-ink-500">Last 30 days</p>
                    </div>
                    <TrendingUp class="w-5 h-5 text-success-600" />
                </div>
                <div class="h-[200px]">
                    <Line :data="revenueChartData" :options="chartOptions" />
                </div>
            </div>
        </div>

        <!-- Recent Activity -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Recent Users -->
            <div class="bg-white rounded-2xl border border-line">
                <div class="p-5 border-b border-line flex items-center justify-between">
                    <h3 class="font-semibold text-ink-900">Recent Users</h3>
                    <Link href="/admin/users" class="text-sm text-accent-600 hover:text-accent-700">
                        View All
                    </Link>
                </div>
                <div class="divide-y divide-line">
                    <div
                        v-for="user in recentUsers"
                        :key="user.id"
                        class="p-4 flex items-center gap-4"
                    >
                        <div class="w-10 h-10 bg-paper-deep rounded-full flex items-center justify-center text-ink-900 font-medium">
                            {{ user.name.charAt(0) }}
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-ink-900 font-medium truncate">{{ user.name }}</p>
                            <p class="text-sm text-ink-500 truncate">{{ user.email }}</p>
                        </div>
                        <p class="text-xs text-slate-500">{{ formatDate(user.created_at) }}</p>
                    </div>
                </div>
            </div>

            <!-- Pending Payments -->
            <div class="bg-white rounded-2xl border border-line">
                <div class="p-5 border-b border-line flex items-center justify-between">
                    <div class="flex items-center gap-2">
                        <h3 class="font-semibold text-ink-900">Pending Payments</h3>
                        <span
                            v-if="stats.pending_payments > 0"
                            class="px-2 py-0.5 bg-warning-500/20 text-warning-600 text-xs font-medium rounded-full"
                        >
                            {{ stats.pending_payments }}
                        </span>
                    </div>
                    <Link href="/admin/payments?status=pending" class="text-sm text-accent-600 hover:text-accent-700">
                        View All
                    </Link>
                </div>
                <div v-if="pendingPayments.length === 0" class="p-8 text-center">
                    <Clock class="w-10 h-10 text-slate-600 mx-auto mb-2" />
                    <p class="text-ink-500">No pending payments</p>
                </div>
                <div v-else class="divide-y divide-line">
                    <div
                        v-for="payment in pendingPayments"
                        :key="payment.id"
                        class="p-4 flex items-center gap-4"
                    >
                        <div class="w-10 h-10 bg-warning-500/20 rounded-full flex items-center justify-center">
                            <Clock class="w-5 h-5 text-warning-600" />
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-ink-900 font-medium truncate">{{ payment.user.name }}</p>
                            <p class="text-sm text-ink-500">
                                {{ payment.subscription?.plan?.name || 'Plan' }} &middot; {{ payment.transaction_id }}
                            </p>
                        </div>
                        <p class="text-ink-900 font-semibold">{{ formatPrice(payment.amount) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-paper-subtle rounded-xl p-4 border border-line">
                <p class="text-ink-500 text-sm">Total Profiles</p>
                <p class="text-xl font-bold text-ink-900 mt-1">{{ stats.total_profiles }}</p>
            </div>
            <div class="bg-paper-subtle rounded-xl p-4 border border-line">
                <p class="text-ink-500 text-sm">Total Products</p>
                <p class="text-xl font-bold text-ink-900 mt-1">{{ stats.total_products }}</p>
            </div>
            <div class="bg-paper-subtle rounded-xl p-4 border border-line">
                <p class="text-ink-500 text-sm">New Users (Month)</p>
                <p class="text-xl font-bold text-ink-900 mt-1">{{ stats.new_users_this_month }}</p>
            </div>
            <div class="bg-paper-subtle rounded-xl p-4 border border-line">
                <p class="text-ink-500 text-sm">Pending Payments</p>
                <p class="text-xl font-bold text-warning-600 mt-1">{{ stats.pending_payments }}</p>
            </div>
        </div>
    </div>
</template>

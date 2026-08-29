<script setup lang="ts">
import { ref, watch } from 'vue'
import { Head, Link, router } from '@inertiajs/vue3'
import AdminLayout from '@/Layouts/AdminLayout.vue'
import { Button } from '@/Components/ui/button'
import { useDebounceFn } from '@vueuse/core'
import {
    CreditCard,
    CheckCircle,
    XCircle,
    Clock,
    ChevronLeft,
    ChevronRight,
    Play,
    Ban,
    Plus,
    Eye,
    Calendar,
} from 'lucide-vue-next'

interface Plan {
    id: number
    name: string
    price: number
}

interface User {
    id: number
    name: string
    email: string
}

interface Subscription {
    id: number
    status: string
    starts_at: string | null
    ends_at: string | null
    cancelled_at: string | null
    created_at: string
    user: User
    plan: Plan
}

interface PaginatedSubscriptions {
    data: Subscription[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    links: { url: string | null; label: string; active: boolean }[]
}

interface Filters {
    status: string
    plan_id: string
}

interface Props {
    subscriptions: PaginatedSubscriptions
    plans: Plan[]
    filters: Filters
}

const props = defineProps<Props>()

defineOptions({
    layout: AdminLayout,
})

const status = ref(props.filters.status)
const planId = ref(props.filters.plan_id)
const processing = ref<number | null>(null)
const extendMonths = ref<{ [key: number]: number }>({})

const applyFilters = useDebounceFn(() => {
    router.get('/admin/subscriptions', {
        status: status.value || undefined,
        plan_id: planId.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}, 300)

watch([status, planId], applyFilters)

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
    }).format(price).replace('BDT', '৳')
}

const formatDate = (dateString: string | null) => {
    if (!dateString) return '-'
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
    })
}

const getStatusIcon = (status: string) => {
    switch (status) {
        case 'active': return CheckCircle
        case 'pending': return Clock
        case 'cancelled': return XCircle
        default: return Clock
    }
}

const getStatusColor = (status: string) => {
    switch (status) {
        case 'active': return 'bg-green-500/20 text-green-400'
        case 'pending': return 'bg-amber-500/20 text-amber-400'
        case 'cancelled': return 'bg-red-500/20 text-red-400'
        default: return 'bg-slate-500/20 text-slate-400'
    }
}

const activate = (subscription: Subscription) => {
    if (confirm(`Activate subscription for ${subscription.user.name}?`)) {
        processing.value = subscription.id
        router.post(`/admin/subscriptions/${subscription.id}/activate`, {}, {
            onFinish: () => processing.value = null,
        })
    }
}

const cancel = (subscription: Subscription) => {
    if (confirm(`Cancel subscription for ${subscription.user.name}?`)) {
        processing.value = subscription.id
        router.post(`/admin/subscriptions/${subscription.id}/cancel`, {}, {
            onFinish: () => processing.value = null,
        })
    }
}

const extend = (subscription: Subscription) => {
    const months = extendMonths.value[subscription.id] || 1
    if (confirm(`Extend subscription by ${months} month(s) for ${subscription.user.name}?`)) {
        processing.value = subscription.id
        router.post(`/admin/subscriptions/${subscription.id}/extend`, { months }, {
            onFinish: () => processing.value = null,
        })
    }
}

const getDaysRemaining = (endsAt: string | null) => {
    if (!endsAt) return null
    const end = new Date(endsAt)
    const now = new Date()
    const diff = Math.ceil((end.getTime() - now.getTime()) / (1000 * 60 * 60 * 24))
    return diff
}
</script>

<template>
    <Head title="Subscriptions - Admin" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Subscriptions</h1>
                <p class="text-slate-400 mt-1">Manage user subscriptions</p>
            </div>
            <p class="text-slate-400">{{ subscriptions.total }} users</p>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-4">
            <select
                v-model="status"
                class="px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
            >
                <option value="">All Status</option>
                <option value="active">Active</option>
                <option value="pending">Pending</option>
                <option value="cancelled">Cancelled</option>
                <option value="free">Free</option>
            </select>

            <select
                v-model="planId"
                class="px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
            >
                <option value="">All Plans</option>
                <option v-for="plan in plans" :key="plan.id" :value="plan.id">
                    {{ plan.name }}
                </option>
            </select>
        </div>

        <!-- Subscriptions Table -->
        <div class="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-700">
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">User</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Plan</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Status</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Start Date</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">End Date</th>
                            <th class="text-right px-6 py-4 text-sm font-medium text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        <tr
                            v-for="sub in subscriptions.data"
                            :key="sub.user.id"
                            class="hover:bg-slate-700/50 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center text-white font-medium">
                                        {{ sub.user.name.charAt(0) }}
                                    </div>
                                    <div>
                                        <Link
                                            :href="`/admin/users/${sub.user.id}`"
                                            class="text-white font-medium hover:text-orange-400"
                                        >
                                            {{ sub.user.name }}
                                        </Link>
                                        <p class="text-sm text-slate-400">{{ sub.user.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span class="px-2 py-1 bg-purple-500/20 text-purple-400 rounded-lg text-sm font-medium">
                                    {{ sub.plan.name }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-2">
                                    <span :class="['px-2 py-1 rounded-lg text-xs font-medium', getStatusColor(sub.status)]">
                                        {{ sub.status }}
                                    </span>
                                    <span
                                        v-if="sub.status === 'active' && getDaysRemaining(sub.ends_at) !== null"
                                        :class="[
                                            'text-xs',
                                            getDaysRemaining(sub.ends_at)! <= 7 ? 'text-amber-400' : 'text-slate-500'
                                        ]"
                                    >
                                        {{ getDaysRemaining(sub.ends_at) }}d left
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-slate-400">
                                {{ formatDate(sub.starts_at) }}
                            </td>
                            <td class="px-6 py-4 text-slate-400">
                                {{ formatDate(sub.ends_at) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Pending: Activate -->
                                    <Button
                                        v-if="sub.status === 'pending'"
                                        @click="activate(sub)"
                                        :disabled="processing === sub.id"
                                        size="sm"
                                        class="bg-green-600 hover:bg-green-700 text-white"
                                    >
                                        <Play class="w-4 h-4 mr-1" />
                                        Activate
                                    </Button>

                                    <!-- Active: Cancel -->
                                    <Button
                                        v-if="sub.status === 'active'"
                                        @click="cancel(sub)"
                                        :disabled="processing === sub.id"
                                        size="sm"
                                        class="bg-red-600 hover:bg-red-700 text-white"
                                    >
                                        <Ban class="w-4 h-4 mr-1" />
                                        Cancel
                                    </Button>

                                    <!-- Active: Extend -->
                                    <div v-if="sub.status === 'active'" class="flex items-center gap-1">
                                        <select
                                            v-model="extendMonths[sub.id]"
                                            class="px-2 py-1 bg-slate-700 border border-slate-600 rounded text-white text-sm"
                                        >
                                            <option :value="1">1 mo</option>
                                            <option :value="2">2 mo</option>
                                            <option :value="3">3 mo</option>
                                            <option :value="6">6 mo</option>
                                            <option :value="12">12 mo</option>
                                        </select>
                                        <Button
                                            @click="extend(sub)"
                                            :disabled="processing === sub.id"
                                            size="sm"
                                            class="bg-purple-600 hover:bg-purple-700 text-white"
                                        >
                                            <Plus class="w-4 h-4" />
                                        </Button>
                                    </div>

                                    <!-- View User -->
                                    <Link
                                        :href="`/admin/users/${sub.user.id}`"
                                        class="p-2 bg-slate-700 hover:bg-slate-600 text-white rounded-lg transition-colors"
                                    >
                                        <Eye class="w-4 h-4" />
                                    </Link>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="subscriptions.last_page > 1" class="px-6 py-4 border-t border-slate-700 flex items-center justify-between">
                <p class="text-sm text-slate-400">
                    Showing {{ (subscriptions.current_page - 1) * subscriptions.per_page + 1 }} to
                    {{ Math.min(subscriptions.current_page * subscriptions.per_page, subscriptions.total) }} of
                    {{ subscriptions.total }} users
                </p>
                <div class="flex items-center gap-2">
                    <template v-for="link in subscriptions.links" :key="link.label">
                        <Link
                            v-if="link.url"
                            :href="link.url"
                            :class="[
                                'px-3 py-1.5 rounded-lg text-sm transition-colors',
                                link.active
                                    ? 'bg-orange-500 text-white'
                                    : 'bg-slate-700 text-slate-300 hover:bg-slate-600'
                            ]"
                            v-html="link.label"
                        />
                        <span
                            v-else
                            class="px-3 py-1.5 text-slate-500 text-sm"
                            v-html="link.label"
                        />
                    </template>
                </div>
            </div>
        </div>
    </div>
</template>

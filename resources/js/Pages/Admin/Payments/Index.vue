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
    DollarSign,
    Eye,
    Check,
    X,
    RefreshCw,
} from 'lucide-vue-next'

interface User {
    id: number
    name: string
    email: string
}

interface Plan {
    id: number
    name: string
}

interface Subscription {
    id: number
    plan: Plan
}

interface Payment {
    id: number
    amount: number
    method: string
    transaction_id: string
    status: string
    notes: string | null
    created_at: string
    user: User
    subscription: Subscription | null
}

interface PaginatedPayments {
    data: Payment[]
    current_page: number
    last_page: number
    per_page: number
    total: number
    links: { url: string | null; label: string; active: boolean }[]
}

interface Stats {
    total: number
    completed: number
    pending: number
    pending_count: number
}

interface Filters {
    status: string
    method: string
    from: string
    to: string
}

interface Props {
    payments: PaginatedPayments
    stats: Stats
    filters: Filters
}

const props = defineProps<Props>()

defineOptions({
    layout: AdminLayout,
})

const status = ref(props.filters.status)
const method = ref(props.filters.method)
const dateFrom = ref(props.filters.from)
const dateTo = ref(props.filters.to)
const processing = ref<number | null>(null)
const rejectReason = ref('')
const showRejectModal = ref<number | null>(null)

const applyFilters = useDebounceFn(() => {
    router.get('/admin/payments', {
        status: status.value || undefined,
        method: method.value || undefined,
        from: dateFrom.value || undefined,
        to: dateTo.value || undefined,
    }, {
        preserveState: true,
        preserveScroll: true,
    })
}, 300)

watch([status, method, dateFrom, dateTo], applyFilters)

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('en-BD', {
        style: 'currency',
        currency: 'BDT',
        minimumFractionDigits: 0,
    }).format(price).replace('BDT', '৳')
}

const formatDateTime = (dateString: string) => {
    return new Date(dateString).toLocaleDateString('en-US', {
        month: 'short',
        day: 'numeric',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
    })
}

const getStatusColor = (status: string) => {
    switch (status) {
        case 'completed': return 'bg-green-500/20 text-green-400'
        case 'pending': return 'bg-amber-500/20 text-amber-400'
        case 'failed': return 'bg-red-500/20 text-red-400'
        case 'refunded': return 'bg-purple-500/20 text-purple-400'
        default: return 'bg-slate-500/20 text-slate-400'
    }
}

const getMethodLabel = (method: string) => {
    switch (method.toLowerCase()) {
        case 'bkash': return 'bKash'
        case 'nagad': return 'Nagad'
        case 'rocket': return 'Rocket'
        case 'bank': return 'Bank Transfer'
        default: return method
    }
}

const approve = (payment: Payment) => {
    if (confirm(`Approve payment from ${payment.user.name}?`)) {
        processing.value = payment.id
        router.post(`/admin/payments/${payment.id}/approve`, {}, {
            onFinish: () => processing.value = null,
        })
    }
}

const openRejectModal = (payment: Payment) => {
    showRejectModal.value = payment.id
    rejectReason.value = ''
}

const reject = (payment: Payment) => {
    processing.value = payment.id
    router.post(`/admin/payments/${payment.id}/reject`, {
        reason: rejectReason.value,
    }, {
        onFinish: () => {
            processing.value = null
            showRejectModal.value = null
            rejectReason.value = ''
        },
    })
}

const refund = (payment: Payment) => {
    if (confirm(`Mark payment from ${payment.user.name} as refunded?`)) {
        processing.value = payment.id
        router.post(`/admin/payments/${payment.id}/refund`, {}, {
            onFinish: () => processing.value = null,
        })
    }
}
</script>

<template>
    <Head title="Payments - Admin" />

    <div class="space-y-6">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-white">Payments</h1>
                <p class="text-slate-400 mt-1">Manage payment transactions</p>
            </div>
        </div>

        <!-- Stats -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            <div class="bg-slate-800 rounded-2xl p-5 border border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm">Total Revenue</p>
                        <p class="text-2xl font-bold text-white mt-1">{{ formatPrice(stats.total) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-500/20 rounded-xl flex items-center justify-center">
                        <DollarSign class="w-6 h-6 text-green-400" />
                    </div>
                </div>
            </div>
            <div class="bg-slate-800 rounded-2xl p-5 border border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm">Completed</p>
                        <p class="text-2xl font-bold text-white mt-1">{{ formatPrice(stats.completed) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-500/20 rounded-xl flex items-center justify-center">
                        <CheckCircle class="w-6 h-6 text-blue-400" />
                    </div>
                </div>
            </div>
            <div class="bg-slate-800 rounded-2xl p-5 border border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm">Pending</p>
                        <p class="text-2xl font-bold text-white mt-1">{{ formatPrice(stats.pending) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-amber-500/20 rounded-xl flex items-center justify-center">
                        <Clock class="w-6 h-6 text-amber-400" />
                    </div>
                </div>
            </div>
            <div class="bg-slate-800 rounded-2xl p-5 border border-slate-700">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-slate-400 text-sm">Pending Count</p>
                        <p class="text-2xl font-bold text-amber-400 mt-1">{{ stats.pending_count }}</p>
                    </div>
                    <div class="w-12 h-12 bg-orange-500/20 rounded-xl flex items-center justify-center">
                        <CreditCard class="w-6 h-6 text-orange-400" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters -->
        <div class="flex flex-wrap gap-4">
            <select
                v-model="status"
                class="px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
            >
                <option value="">All Status</option>
                <option value="pending">Pending</option>
                <option value="completed">Completed</option>
                <option value="failed">Failed</option>
                <option value="refunded">Refunded</option>
            </select>

            <select
                v-model="method"
                class="px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
            >
                <option value="">All Methods</option>
                <option value="bkash">bKash</option>
                <option value="nagad">Nagad</option>
                <option value="rocket">Rocket</option>
                <option value="bank">Bank Transfer</option>
            </select>

            <input
                v-model="dateFrom"
                type="date"
                placeholder="From Date"
                class="px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
            />

            <input
                v-model="dateTo"
                type="date"
                placeholder="To Date"
                class="px-4 py-2.5 bg-slate-800 border border-slate-700 rounded-xl text-white focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
            />
        </div>

        <!-- Payments Table -->
        <div class="bg-slate-800 rounded-2xl border border-slate-700 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-slate-700">
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">User</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Plan</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Method</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Transaction ID</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Amount</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Status</th>
                            <th class="text-left px-6 py-4 text-sm font-medium text-slate-400">Date</th>
                            <th class="text-right px-6 py-4 text-sm font-medium text-slate-400">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-700">
                        <tr
                            v-for="payment in payments.data"
                            :key="payment.id"
                            class="hover:bg-slate-700/50 transition-colors"
                        >
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-slate-700 rounded-full flex items-center justify-center text-white font-medium">
                                        {{ payment.user.name.charAt(0) }}
                                    </div>
                                    <div>
                                        <Link
                                            :href="`/admin/users/${payment.user.id}`"
                                            class="text-white font-medium hover:text-orange-400"
                                        >
                                            {{ payment.user.name }}
                                        </Link>
                                        <p class="text-sm text-slate-400">{{ payment.user.email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <span v-if="payment.subscription" class="text-purple-400">
                                    {{ payment.subscription.plan.name }}
                                </span>
                                <span v-else class="text-slate-500">-</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-300">{{ getMethodLabel(payment.method) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-slate-400 font-mono text-sm">{{ payment.transaction_id }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span class="text-white font-semibold">{{ formatPrice(payment.amount) }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <span :class="['px-2 py-1 rounded-lg text-xs font-medium', getStatusColor(payment.status)]">
                                    {{ payment.status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-400 text-sm">
                                {{ formatDateTime(payment.created_at) }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <!-- Pending: Approve/Reject -->
                                    <template v-if="payment.status === 'pending'">
                                        <Button
                                            @click="approve(payment)"
                                            :disabled="processing === payment.id"
                                            size="sm"
                                            class="bg-green-600 hover:bg-green-700 text-white"
                                        >
                                            <Check class="w-4 h-4 mr-1" />
                                            Approve
                                        </Button>
                                        <Button
                                            @click="openRejectModal(payment)"
                                            :disabled="processing === payment.id"
                                            size="sm"
                                            class="bg-red-600 hover:bg-red-700 text-white"
                                        >
                                            <X class="w-4 h-4 mr-1" />
                                            Reject
                                        </Button>
                                    </template>

                                    <!-- Completed: Refund -->
                                    <Button
                                        v-if="payment.status === 'completed'"
                                        @click="refund(payment)"
                                        :disabled="processing === payment.id"
                                        size="sm"
                                        class="bg-purple-600 hover:bg-purple-700 text-white"
                                    >
                                        <RefreshCw class="w-4 h-4 mr-1" />
                                        Refund
                                    </Button>

                                    <!-- View User -->
                                    <Link
                                        :href="`/admin/users/${payment.user.id}`"
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
            <div v-if="payments.last_page > 1" class="px-6 py-4 border-t border-slate-700 flex items-center justify-between">
                <p class="text-sm text-slate-400">
                    Showing {{ (payments.current_page - 1) * payments.per_page + 1 }} to
                    {{ Math.min(payments.current_page * payments.per_page, payments.total) }} of
                    {{ payments.total }} payments
                </p>
                <div class="flex items-center gap-2">
                    <template v-for="link in payments.links" :key="link.label">
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

        <!-- Reject Modal -->
        <div
            v-if="showRejectModal !== null"
            class="fixed inset-0 bg-black/50 flex items-center justify-center z-50"
            @click.self="showRejectModal = null"
        >
            <div class="bg-slate-800 rounded-2xl p-6 w-full max-w-md border border-slate-700">
                <h3 class="text-lg font-semibold text-white mb-4">Reject Payment</h3>
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm text-slate-400 mb-2">Reason (optional)</label>
                        <textarea
                            v-model="rejectReason"
                            rows="3"
                            class="w-full px-4 py-3 bg-slate-900 border border-slate-700 rounded-xl text-white placeholder-slate-500 focus:border-orange-500 focus:ring-1 focus:ring-orange-500"
                            placeholder="Enter rejection reason..."
                        ></textarea>
                    </div>
                    <div class="flex gap-3">
                        <Button
                            @click="showRejectModal = null"
                            class="flex-1 bg-slate-700 hover:bg-slate-600 text-white"
                        >
                            Cancel
                        </Button>
                        <Button
                            @click="reject(payments.data.find(p => p.id === showRejectModal)!)"
                            :disabled="processing === showRejectModal"
                            class="flex-1 bg-red-600 hover:bg-red-700 text-white"
                        >
                            Reject Payment
                        </Button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

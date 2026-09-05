<script setup lang="ts">
import { Receipt, Clock, CheckCircle, XCircle, AlertCircle } from 'lucide-vue-next'

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
    status: 'pending' | 'completed' | 'failed' | 'refunded'
    created_at: string
    subscription: Subscription | null
}

interface Props {
    payments: Payment[]
}

defineProps<Props>()

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
        year: 'numeric',
    })
}

const getMethodLabel = (method: string) => {
    const labels: Record<string, string> = {
        bkash: 'bKash',
        nagad: 'Nagad',
        rocket: 'Rocket',
        bank: 'Bank Transfer',
    }
    return labels[method] || method
}

const getStatusConfig = (status: string) => {
    const configs: Record<string, { icon: any; class: string; label: string }> = {
        pending: {
            icon: Clock,
            class: 'bg-amber-100 text-amber-600',
            label: 'Pending',
        },
        completed: {
            icon: CheckCircle,
            class: 'bg-green-100 text-green-600',
            label: 'Completed',
        },
        failed: {
            icon: XCircle,
            class: 'bg-red-100 text-red-600',
            label: 'Failed',
        },
        refunded: {
            icon: AlertCircle,
            class: 'bg-slate-100 text-slate-600',
            label: 'Refunded',
        },
    }
    return configs[status] || configs.pending
}
</script>

<template>
    <div class="rounded-2xl border border-line bg-white p-6 shadow-sm">
        <h3 class="flex items-center gap-2 text-base font-semibold text-slate-900">
            <Receipt class="h-5 w-5 accent-text" />
            Payment History
        </h3>

        <!-- Empty State -->
        <div v-if="payments.length === 0" class="py-10 text-center">
            <div class="mx-auto mb-4 flex h-16 w-16 items-center justify-center rounded-2xl bg-slate-50">
                <Receipt class="h-8 w-8 text-slate-400" />
            </div>
            <p class="text-sm text-slate-400">No payment history yet</p>
        </div>

        <!-- Payments Table -->
        <div v-else class="mt-5 overflow-x-auto">
            <table class="w-full min-w-[560px] text-left">
                <thead>
                    <tr class="text-xs uppercase tracking-wide text-slate-400">
                        <th class="pb-3 pr-4 font-medium">Plan</th>
                        <th class="pb-3 pr-4 font-medium">Method</th>
                        <th class="pb-3 pr-4 font-medium">Status</th>
                        <th class="pb-3 pr-4 font-medium">Date</th>
                        <th class="pb-3 text-right font-medium">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    <tr
                        v-for="payment in payments"
                        :key="payment.id"
                        class="text-sm"
                    >
                        <td class="py-4 pr-4">
                            <p class="font-medium text-slate-900">
                                {{ payment.subscription?.plan?.name || 'Subscription' }} Plan
                            </p>
                        </td>
                        <td class="py-4 pr-4 text-slate-500">
                            <p>{{ getMethodLabel(payment.method) }}</p>
                            <p class="text-xs text-slate-400">{{ payment.transaction_id }}</p>
                        </td>
                        <td class="py-4 pr-4">
                            <span
                                :class="[
                                    'inline-flex rounded-full px-2.5 py-0.5 text-xs font-medium',
                                    payment.status === 'completed'
                                        ? 'bg-emerald-50 text-emerald-700'
                                        : payment.status === 'pending'
                                            ? 'bg-amber-50 text-amber-700'
                                            : payment.status === 'failed'
                                                ? 'bg-rose-50 text-rose-700'
                                                : 'bg-slate-100 text-slate-600'
                                ]"
                            >
                                {{ getStatusConfig(payment.status).label }}
                            </span>
                        </td>
                        <td class="py-4 pr-4 text-slate-500">
                            {{ formatDate(payment.created_at) }}
                        </td>
                        <td class="py-4 text-right font-semibold text-slate-900">
                            {{ formatPrice(payment.amount) }}
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

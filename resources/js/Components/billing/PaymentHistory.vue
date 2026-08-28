<script setup lang="ts">
import { Card, CardContent, CardHeader, CardTitle } from '@/Components/ui/card'
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
    <Card>
        <CardHeader>
            <CardTitle class="flex items-center gap-2">
                <Receipt class="w-5 h-5 text-primary-600" />
                Payment History
            </CardTitle>
        </CardHeader>
        <CardContent>
            <!-- Empty State -->
            <div v-if="payments.length === 0" class="text-center py-8">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center mx-auto mb-4">
                    <Receipt class="w-8 h-8 text-slate-400" />
                </div>
                <p class="text-slate-500">No payment history yet</p>
            </div>

            <!-- Payments List -->
            <div v-else class="divide-y divide-slate-100">
                <div
                    v-for="payment in payments"
                    :key="payment.id"
                    class="py-4 first:pt-0 last:pb-0"
                >
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-4">
                            <div
                                :class="[
                                    'w-10 h-10 rounded-xl flex items-center justify-center',
                                    getStatusConfig(payment.status).class
                                ]"
                            >
                                <component
                                    :is="getStatusConfig(payment.status).icon"
                                    class="w-5 h-5"
                                />
                            </div>
                            <div>
                                <p class="font-medium text-slate-900">
                                    {{ payment.subscription?.plan?.name || 'Subscription' }} Plan
                                </p>
                                <p class="text-sm text-slate-500">
                                    {{ getMethodLabel(payment.method) }} &middot; {{ payment.transaction_id }}
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-semibold text-slate-900">
                                {{ formatPrice(payment.amount) }}
                            </p>
                            <p class="text-sm text-slate-500">
                                {{ formatDate(payment.created_at) }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </CardContent>
    </Card>
</template>
